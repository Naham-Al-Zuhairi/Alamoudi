<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-site') ?? false;
    }

    public function rules(): array
    {
        $tables = ['slides' => 'hero_slides', 'sections' => 'page_sections', 'achievements' => 'achievements', 'categories' => 'project_categories', 'projects' => 'projects', 'locations' => 'contact_locations', 'settings' => 'site_settings', 'statistics' => 'statistics'];
        $type = $this->route('type');
        abort_unless(isset($tables[$type]), 404);

        $rules = [
            'slug' => ['required', 'alpha_dash', 'max:100', Rule::unique($tables[$type])->ignore($this->route('id'))],
            'title_ar' => 'nullable|string|max:255', 'title_en' => 'nullable|string|max:255',
            'position' => 'required|integer|min:0|max:100000', 'is_published' => 'required|boolean',
        ];
        if ($type === 'statistics') {
            $rules['title_ar'] = 'required|string|max:255';
            $rules['title_en'] = 'required|string|max:255';
            $rules['value'] = 'required|integer|min:0|max:1000000';
        } else {
            $rules += [
                'body_ar' => 'nullable|string|max:10000',
                'body_en' => 'nullable|string|max:10000',
                'image' => ['nullable', 'string', 'max:500', 'regex:~^/(?:media|storage/cms)/[A-Za-z0-9/_-]+\.(?:png|jpe?g|webp)$~i'],
            ];
        }
        if ($type === 'projects') {
            $rules['project_category_id'] = 'nullable|exists:project_categories,id';
        }
        if ($type === 'locations') {
            $rules += ['email' => 'nullable|email|max:255', 'phone' => 'nullable|string|max:60', 'fax' => 'nullable|string|max:60'];
        }

        return $rules;
    }
}
