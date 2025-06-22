<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ApplicationSettings extends Component
{
    use WithFileUploads;

    // Основные настройки компании
    public string $company_name = '';
    public string $currency = '';
    public string $timezone = '';
    public $company_logo;
    public ?string $current_logo = null;

    protected array $rules = [
        'company_name' => 'required|string|max:255',
        'currency' => 'required|string|max:10',
        'timezone' => 'required|string',
        'company_logo' => 'nullable|image|max:2048', // 2MB max
    ];

    protected array $messages = [
        'company_name.required' => 'Название компании обязательно для заполнения',
        'company_name.max' => 'Название компании не может быть длиннее 255 символов',
        'currency.required' => 'Валюта обязательна для заполнения',
        'currency.max' => 'Код валюты не может быть длиннее 10 символов',
        'timezone.required' => 'Часовой пояс обязателен для выбора',
        'company_logo.image' => 'Логотип должен быть изображением',
        'company_logo.max' => 'Размер логотипа не должен превышать 2MB',
    ];

    public function mount(): void
    {
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $this->company_name = Setting::get('company_name', 'Моя Компания');
        $this->currency = Setting::get('currency', 'RUB');
        $this->timezone = Setting::get('timezone', 'Europe/Moscow');
        $this->current_logo = Setting::get('company_logo');
    }

    public function save(): void
    {
        $this->validate();

        try {
            // Сохраняем логотип если загружен новый
            if ($this->company_logo) {
                // Удаляем старый логотип
                if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
                    Storage::disk('public')->delete($this->current_logo);
                }

                // Сохраняем новый логотип
                $logoPath = $this->company_logo->store('logos', 'public');
                Setting::set('company_logo', $logoPath, 'file', 'company');
                $this->current_logo = $logoPath;
                $this->company_logo = null;
            }

            // Сохраняем остальные настройки
            Setting::set('company_name', $this->company_name, 'string', 'company');
            Setting::set('currency', $this->currency, 'string', 'company');
            Setting::set('timezone', $this->timezone, 'string', 'company');

            // Очищаем кеш группы
            Setting::clearGroupCache('company');

            $this->dispatch('settings-updated');
            session()->flash('success', 'Настройки успешно сохранены');

        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка при сохранении настроек: ' . $e->getMessage());
        }
    }

    public function removeLogo(): void
    {
        if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
            Storage::disk('public')->delete($this->current_logo);
        }

        Setting::set('company_logo', null, 'file', 'company');
        Setting::clearGroupCache('company');
        
        $this->current_logo = null;
        session()->flash('success', 'Логотип удален');
    }

    public function getTimezones(): array
    {
        return [
            'Europe/Moscow' => 'Москва (UTC+3)',
            'Europe/Kiev' => 'Киев (UTC+2)',
            'Europe/Minsk' => 'Минск (UTC+3)',
            'Asia/Almaty' => 'Алматы (UTC+6)',
            'Asia/Tashkent' => 'Ташкент (UTC+5)',
            'Asia/Yekaterinburg' => 'Екатеринбург (UTC+5)',
            'Asia/Novosibirsk' => 'Новосибирск (UTC+7)',
            'Asia/Krasnoyarsk' => 'Красноярск (UTC+7)',
            'Asia/Irkutsk' => 'Иркутск (UTC+8)',
            'Asia/Vladivostok' => 'Владивосток (UTC+10)',
        ];
    }

    public function getCurrencies(): array
    {
        return [
            'RUB' => 'Российский рубль (₽)',
            'USD' => 'Доллар США ($)',
            'EUR' => 'Евро (€)',
            'UAH' => 'Украинская гривна (₴)',
            'BYN' => 'Белорусский рубль (Br)',
            'KZT' => 'Казахстанский тенге (₸)',
            'UZS' => 'Узбекский сум (сўм)',
        ];
    }

    public function render()
    {
        return view('livewire.settings.application-settings');
    }
} 