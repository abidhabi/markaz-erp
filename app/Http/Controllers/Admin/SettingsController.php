<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Routes\Events\LocaleHasBeenSetEvent;
use Nnjeim\World\World;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = app('App\Settings\CompanySettings');
        $pageTitle = __('General Settings');

        return view('pages.settings.company', compact(
            'pageTitle',
            'settings'
        ));
    }

    public function updateCompany(Request $request)
    {
        $settings = app('App\Settings\CompanySettings');

        $settings->name = $request->name ?? $settings->name;
        $settings->contact_person = $request->contact_person ?? $settings->contact_person;
        $settings->address = $request->address ?? $settings->address;
        $settings->country = $request->country ?? $settings->country;
        $settings->city = $request->city ?? $settings->city;
        $settings->province = $request->province ?? $settings->province;
        $settings->postal_code = $request->postal_code ?? $settings->postal_code;
        $settings->email = $request->email ?? $settings->email;
        $settings->phone = $request->phone ?? $settings->phone;
        $settings->mobile = $request->mobile ?? $settings->mobile;
        $settings->fax = $request->fax ?? $settings->fax;
        $settings->website_url = $request->website_url ?? $settings->website_url;
        $settings->save();
        
        return redirect()->route('settings.index')->with('success', __('Company Settings has been updated'));
    }

    public function locale()
    {
        $settings = app('App\Settings\LocalizationSettings');
        $pageTitle = 'Localization';
        $country = World::countries([
            'fields' => 'iso2',
            'filters' => [
                'iso2' => $settings->country,
            ],
        ])->data->first();
        $language = World::languages(['filters' => [
            'code' => $settings->lang,
        ]])->data->first();

        return view('pages.settings.locale', compact(
            'pageTitle',
            'settings',
            'country',
            'language'
        ));
    }

    public function updateLocale(Request $request)
    {
        $settings = app('App\Settings\LocalizationSettings');
        $settings->country = $request->country ?? $settings->country;
        $settings->date_format = $request->date_format ?? $settings->date_format;
        $settings->timezone = $request->timezone ?? $settings->timezone;
        $settings->lang = $request->lang ?? $settings->lang;
        $settings->currency_symbol = $request->currency_symbol ?? $settings->currency_symbol;
        $settings->currency_code = $request->currency_code ?? $settings->currency_code;

        $isInstalled = Locales::isInstalled($settings->lang);
        if ($isInstalled) {
            $locale = Locales::get($settings->lang);
            LocaleHasBeenSetEvent::dispatch($locale);
        }
        $settings->save();
        
        return redirect()->route('settings.locale')->with('success', __('Locale Settings has been updated'));
    }

    public function theme()
    {
        $settings = app('App\Settings\ThemeSettings');
        $pageTitle = 'Theme Settings';

        return view('pages.settings.theme', compact(
            'pageTitle',
            'settings'
        ));
    }

    public function updateTheme(Request $request)
    {
        $settings = app('App\Settings\ThemeSettings');
        $side_img = $settings->sidebar_img;
        $logo_light = $settings->logo_light;
        $logo_dark = $settings->logo_dark;
        $favicon = $settings->favicon;
        
        if ($request->hasFile('side_img')) {
            $side_img = Str::random(8).'.'.$request->side_img->extension();
            $request->side_img->move(public_path('storage/settings/theme'), $side_img);
        }
        if ($request->hasFile('logo_light')) {
            $logo_light = Str::random(5).'_light.'.$request->logo_light->extension();
            $request->logo_light->move(public_path('storage/settings/theme'), $logo_light);
        }
        if ($request->hasFile('logo_dark')) {
            $logo_dark = Str::random(5).'_dark.'.$request->logo_dark->extension();
            $request->logo_dark->move(public_path('storage/settings/theme'), $logo_dark);
        }
        if ($request->hasFile('favicon')) {
            $favicon = Str::random(5).'_favicon.'.$request->favicon->extension();
            $request->favicon->move(public_path('storage/settings/theme'), $favicon);
        }
        
        $settings->name = $request->name ?? $settings->name;
        $settings->logo_light = $logo_light;
        $settings->logo_dark = $logo_dark;
        $settings->favicon = $favicon;
        $settings->theme = $request->theme ?? $settings->theme;
        $settings->layout = $request->layout ?? $settings->layout;
        $settings->color_scheme = $request->color_scheme ?? $settings->color_scheme;
        $settings->layout_width = $request->layout_width ?? $settings->layout_width;
        $settings->layout_position = $request->layout_pos ?? $settings->layout_position;
        $settings->topbar_color = $request->topbar_color ?? $settings->topbar_color;
        $settings->sidebar_size = $request->sidebar_size ?? $settings->sidebar_size;
        $settings->sidebar_view = $request->sidebar_view ?? $settings->sidebar_view;
        $settings->sidebar_img = $side_img;
        $settings->sidebar_color = $request->sidebar_color ?? $settings->sidebar_color;
        $settings->save();
        
        return redirect()->route('settings.theme')->with('success', __('Theme Settings has been updated'));
    }

    public function invoice()
    {
        $settings = app('App\Settings\InvoiceSettings');
        $pageTitle = __('Invoice Settings');

        return view('pages.settings.invoice', compact(
            'settings', 'pageTitle'
        ));
    }

    public function updateInvoice(Request $request)
    {
        $settings = app('App\Settings\InvoiceSettings');
        $request->validate([
            'prefix' => 'required',
            'logo' => 'nullable|file|image',
        ]);

        $imageName = $settings->logo;
        if ($request->hasFile('logo')) {
            $imageName = Str::random(8).'.'.$request->logo->extension();
            $request->logo->move(public_path('storage/settings/invoice'), $imageName);
        }
        
        $settings->prefix = $request->prefix ?? $settings->prefix;
        $settings->logo = $imageName;
        $settings->save();
        
        return back()->with('success', __('Invoice settings has been updated'));
    }

    public function email()
    {
        $settings = app('App\Settings\EmailSettings');
        $pageTitle = __('Email Settings');

        return view('pages.settings.email', compact(
            'settings', 'pageTitle'
        ));
    }

    public function updateEmail(Request $request)
    {
        $settings = app('App\Settings\EmailSettings');
        $request->validate([
            'from_address' => 'required|email',
            'port' => 'required|numeric',
            'host' => 'required',
        ]);

        $settings->mailer = $request->mailer ?? $settings->mailer;
        $settings->from_address = $request->from_address ?? $settings->from_address;
        $settings->from_name = $request->from_name ?? $settings->from_name;
        $settings->host = $request->host ?? $settings->host;
        $settings->port = $request->port ?? $settings->port;
        $settings->enc = $request->enc ?? $settings->enc;
        $settings->domain = $request->domain ?? $settings->domain;
        $settings->user = $request->username ?? $settings->user;
        $settings->password = $request->password ?? $settings->password;
        $settings->save();
        
        return back()->with('success', __('Mail Client settings has been updated'));
    }

    public function salary()
    {
        $settings = app('App\Settings\SalarySetting');
        $pageTitle = __('Salary Settings');

        return view('pages.settings.salary', compact(
            'settings', 'pageTitle'
        ));
    }

    public function updateSalarySettings(Request $request)
    {
        $settings = app('App\Settings\SalarySetting');
        $request->validate([
            'enable_pf' => 'nullable|string',
            'enable_esi' => 'nullable|string',
            'emp_esi' => 'nullable|numeric',
            'da_percent' => 'nullable|numeric',
            'hra_percent' => 'nullable|numeric',
            'emp_pf' => 'nullable|numeric',
            'company_pf' => 'nullable|numeric',
            'company_esi' => 'nullable|numeric',
        ]);

        $settings->enable_da_hra = ! empty($request->enable_da_hra);
        $settings->enable_provident_fund = ! empty($request->enable_pf);
        $settings->enable_esi_fund = ! empty($request->enable_esi);
        $settings->da_percent = $request->da_percent ?? $settings->da_percent;
        $settings->hra_percent = $request->hra_percent ?? $settings->hra_percent;
        $settings->emp_pf_percent = $request->emp_pf ?? $request->emp_pf_percent;
        $settings->company_pf_percentage = $request->company_pf ?? $settings->company_pf_percentage;
        $settings->emp_esi_percentage = $request->emp_esi ?? $settings->emp_esi_percentage;
        $settings->company_esi_percentage = $request->company_esi ?? $settings->company_esi_percentage;
        $settings->save();
        
        return back()->with('success', __('Salary settings has been updated'));
    }

    public function accessRights()
    {
        $pageTitle = __('Access Rights Management');

        $roles = Role::where('name', '!=', 'super-admin')->get(); 
        
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            return ucfirst($parts[0]); 
        });

        return view('pages.settings.access-rights', compact('pageTitle', 'roles', 'permissions'));
    }

    public function updateAccessRights(Request $request)
    {
        $permissionsData = $request->input('permissions', []); 

        $roles = Role::where('name', '!=', 'super-admin')->get();

        foreach ($roles as $roleModel) {
            /** @var \Spatie\Permission\Models\Role $roleModel */
            $rolePermissions = [];
            if (isset($permissionsData[$roleModel->id])) {
                $rolePermissions = array_keys($permissionsData[$roleModel->id]);
            }
            $roleModel->syncPermissions($rolePermissions);
        }

        return back()->with('success', 'Access rights updated successfully.');
    }
}