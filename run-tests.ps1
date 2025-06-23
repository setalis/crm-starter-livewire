# Скрипт для запуска тестов CRM системы приема металла
# Поддерживает Windows PowerShell

Write-Host "=== ЗАПУСК ТЕСТОВ CRM СИСТЕМЫ ПРИЕМА МЕТАЛЛА ===" -ForegroundColor Green
Write-Host ""

# Проверяем наличие PHP и composer
try {
    $phpVersion = php --version
    Write-Host "PHP найден: " -NoNewline
    Write-Host ($phpVersion -split "`n")[0] -ForegroundColor Yellow
} catch {
    Write-Host "ОШИБКА: PHP не найден в PATH" -ForegroundColor Red
    exit 1
}

try {
    $composerVersion = composer --version
    Write-Host "Composer найден: " -NoNewline  
    Write-Host ($composerVersion -split "`n")[0] -ForegroundColor Yellow
} catch {
    Write-Host "ОШИБКА: Composer не найден в PATH" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Функция для запуска команды с логированием
function Invoke-TestCommand {
    param(
        [string]$Command,
        [string]$Description,
        [bool]$ContinueOnError = $true
    )
    
    Write-Host "🔄 $Description..." -ForegroundColor Cyan
    
    try {
        Invoke-Expression $Command
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ $Description - ПРОЙДЕН" -ForegroundColor Green
            return $true
        } else {
            Write-Host "❌ $Description - ОШИБКА (код: $LASTEXITCODE)" -ForegroundColor Red
            if (-not $ContinueOnError) {
                exit $LASTEXITCODE
            }
            return $false
        }
    } catch {
        Write-Host "❌ $Description - ИСКЛЮЧЕНИЕ: $($_.Exception.Message)" -ForegroundColor Red
        if (-not $ContinueOnError) {
            exit 1
        }
        return $false
    }
    
    Write-Host ""
}

# Параметры скрипта
param(
    [switch]$Coverage,      # Генерировать отчет о покрытии кода
    [switch]$Unit,          # Запускать только Unit тесты
    [switch]$Feature,       # Запускать только Feature тесты
    [switch]$Browser,       # Запускать Browser тесты (Dusk)
    [switch]$Performance,   # Запускать Performance тесты
    [switch]$All,           # Запускать все тесты (по умолчанию)
    [switch]$Verbose,       # Подробный вывод
    [string]$Filter = "",   # Фильтр для конкретных тестов
    [switch]$Parallel       # Параллельное выполнение (если поддерживается)
)

# Установка флага All по умолчанию
if (-not ($Unit -or $Feature -or $Browser -or $Performance)) {
    $All = $true
}

Write-Host "Параметры запуска:" -ForegroundColor Yellow
Write-Host "  - Coverage: $Coverage"
Write-Host "  - Unit: $Unit"
Write-Host "  - Feature: $Feature" 
Write-Host "  - Browser: $Browser"
Write-Host "  - Performance: $Performance"
Write-Host "  - All: $All"
Write-Host "  - Verbose: $Verbose"
Write-Host "  - Filter: '$Filter'"
Write-Host "  - Parallel: $Parallel"
Write-Host ""

# Подготовка окружения
Write-Host "=== ПОДГОТОВКА ОКРУЖЕНИЯ ===" -ForegroundColor Magenta

# Очистка кэша
Invoke-TestCommand "php artisan config:clear" "Очистка кэша конфигурации"
Invoke-TestCommand "php artisan cache:clear" "Очистка кэша приложения"

# Подготовка базы данных для тестов
Invoke-TestCommand "php artisan migrate:fresh --env=testing" "Подготовка тестовой базы данных"

# Установка зависимостей (если нужно)
if (-not (Test-Path "vendor")) {
    Invoke-TestCommand "composer install --no-interaction" "Установка зависимостей"
}

Write-Host ""

# Массив для отслеживания результатов
$results = @()

# Базовые опции PHPUnit
$phpunitOptions = ""
if ($Verbose) {
    $phpunitOptions += " --verbose"
}
if ($Filter) {
    $phpunitOptions += " --filter='$Filter'"
}
if ($Coverage) {
    $phpunitOptions += " --coverage-html=tests/coverage --coverage-text"
    Write-Host "📊 Покрытие кода будет сохранено в tests/coverage/" -ForegroundColor Yellow
}

# Запуск Unit тестов
if ($Unit -or $All) {
    Write-Host "=== UNIT ТЕСТЫ ===" -ForegroundColor Magenta
    $unitResult = Invoke-TestCommand "php artisan test --testsuite=Unit$phpunitOptions" "Unit тесты"
    $results += @{Name="Unit тесты"; Result=$unitResult}
    Write-Host ""
}

# Запуск Feature тестов
if ($Feature -or $All) {
    Write-Host "=== FEATURE ТЕСТЫ ===" -ForegroundColor Magenta
    $featureResult = Invoke-TestCommand "php artisan test --testsuite=Feature$phpunitOptions" "Feature тесты"
    $results += @{Name="Feature тесты"; Result=$featureResult}
    Write-Host ""
}

# Запуск Browser тестов (Dusk)
if ($Browser -or $All) {
    Write-Host "=== BROWSER ТЕСТЫ (DUSK) ===" -ForegroundColor Magenta
    
    # Проверяем установку Dusk
    if (Test-Path "tests/Browser") {
        # Запуск Chrome/Chromium в headless режиме
        $env:DUSK_HEADLESS_DISABLED = "false"
        $browserResult = Invoke-TestCommand "php artisan dusk" "Browser тесты (Dusk)"
        $results += @{Name="Browser тесты"; Result=$browserResult}
    } else {
        Write-Host "⚠️  Browser тесты не настроены (Laravel Dusk не найден)" -ForegroundColor Yellow
        $results += @{Name="Browser тесты"; Result=$false}
    }
    Write-Host ""
}

# Запуск Performance тестов
if ($Performance -or $All) {
    Write-Host "=== PERFORMANCE ТЕСТЫ ===" -ForegroundColor Magenta
    
    if (Test-Path "tests/Performance") {
        $performanceResult = Invoke-TestCommand "php artisan test tests/Performance$phpunitOptions" "Performance тесты"
        $results += @{Name="Performance тесты"; Result=$performanceResult}
    } else {
        Write-Host "⚠️  Performance тесты не найдены" -ForegroundColor Yellow
        $results += @{Name="Performance тесты"; Result=$false}
    }
    Write-Host ""
}

# Дополнительные проверки
Write-Host "=== ДОПОЛНИТЕЛЬНЫЕ ПРОВЕРКИ ===" -ForegroundColor Magenta

# Статический анализ кода (если установлен PHPStan)
if (Get-Command "phpstan" -ErrorAction SilentlyContinue) {
    $phpstanResult = Invoke-TestCommand "phpstan analyse" "Статический анализ кода (PHPStan)"
    $results += @{Name="PHPStan"; Result=$phpstanResult}
} else {
    Write-Host "⚠️  PHPStan не установлен" -ForegroundColor Yellow
}

# Проверка стиля кода (если установлен PHP CS Fixer)
if (Get-Command "php-cs-fixer" -ErrorAction SilentlyContinue) {
    $codeStyleResult = Invoke-TestCommand "php-cs-fixer fix --dry-run --verbose" "Проверка стиля кода"
    $results += @{Name="Стиль кода"; Result=$codeStyleResult}
} else {
    Write-Host "⚠️  PHP CS Fixer не установлен" -ForegroundColor Yellow
}

Write-Host ""

# Итоговый отчет
Write-Host "=== ИТОГОВЫЙ ОТЧЕТ ===" -ForegroundColor Magenta
Write-Host ""

$totalTests = $results.Count
$passedTests = ($results | Where-Object { $_.Result -eq $true }).Count
$failedTests = $totalTests - $passedTests

foreach ($result in $results) {
    $status = if ($result.Result) { "✅ ПРОЙДЕН" } else { "❌ ПРОВАЛЕН" }
    $color = if ($result.Result) { "Green" } else { "Red" }
    Write-Host "  $($result.Name): " -NoNewline
    Write-Host $status -ForegroundColor $color
}

Write-Host ""
Write-Host "Всего тестовых наборов: $totalTests" -ForegroundColor White
Write-Host "Пройдено: $passedTests" -ForegroundColor Green
Write-Host "Провалено: $failedTests" -ForegroundColor Red

$successRate = if ($totalTests -gt 0) { [math]::Round(($passedTests / $totalTests) * 100, 1) } else { 0 }
Write-Host "Процент успеха: $successRate%" -ForegroundColor $(if ($successRate -ge 80) { "Green" } else { "Yellow" })

Write-Host ""

# Рекомендации
if ($failedTests -gt 0) {
    Write-Host "🔧 РЕКОМЕНДАЦИИ:" -ForegroundColor Yellow
    Write-Host "  1. Проверьте логи ошибок в storage/logs/"
    Write-Host "  2. Убедитесь, что тестовая база данных настроена корректно"
    Write-Host "  3. Проверьте наличие всех зависимостей: composer install"
    Write-Host "  4. Для Browser тестов убедитесь, что Chrome/Chromium установлен"
    Write-Host ""
}

if ($Coverage) {
    Write-Host "📊 Отчет о покрытии кода сохранен в: tests/coverage/index.html" -ForegroundColor Cyan
    
    # Попытка открыть отчет в браузере
    if (Test-Path "tests/coverage/index.html") {
        try {
            Start-Process "tests/coverage/index.html"
            Write-Host "🌐 Отчет открыт в браузере" -ForegroundColor Green
        } catch {
            Write-Host "⚠️  Не удалось открыть отчет автоматически" -ForegroundColor Yellow
        }
    }
}

Write-Host ""
Write-Host "=== ТЕСТИРОВАНИЕ ЗАВЕРШЕНО ===" -ForegroundColor Green

# Возвращаем код выхода
if ($failedTests -gt 0) {
    exit 1
} else {
    exit 0
} 