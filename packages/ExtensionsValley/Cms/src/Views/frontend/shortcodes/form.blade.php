<div class="cms-form-widget" id="cms-form-{{ $form->slug }}" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:24px; margin:22px 0;">
    <h3 style="margin-top:0; margin-bottom:14px;">{{ $form->name }}</h3>

    @if(session('cms_form_success_' . $form->slug))
        <div style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; padding:12px 14px; border-radius:6px; margin-bottom:14px;">
            {{ session('cms_form_success_' . $form->slug) }}
        </div>
    @endif

    @if($errors->hasBag('cms_form_' . $form->slug))
        <div style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:6px; margin-bottom:14px;">
            @foreach($errors->getBag('cms_form_' . $form->slug)->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('cms.frontend.form.submit', ['slug' => $form->slug]) }}" method="POST">
        @csrf
        @foreach($form->fields as $field)
            <div style="margin-bottom:14px;">
                <label style="display:block; font-weight:600; margin-bottom:5px; font-size:0.92rem;">
                    {{ $field->label }}
                    @if($field->is_required) <span style="color:#dc2626;">*</span> @endif
                </label>

                @if($field->field_type === 'textarea')
                    <textarea name="{{ $field->field_name }}" rows="4"
                              placeholder="{{ $field->placeholder }}"
                              {{ $field->is_required ? 'required' : '' }}
                              style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-family:inherit;">{{ old($field->field_name) }}</textarea>
                @elseif($field->field_type === 'select')
                    <select name="{{ $field->field_name }}" {{ $field->is_required ? 'required' : '' }}
                            style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; background:#fff;">
                        <option value="">{{ $field->placeholder ?: '— Select —' }}</option>
                        @foreach($field->options_array as $opt)
                            <option value="{{ $opt }}" {{ old($field->field_name) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                @elseif($field->field_type === 'radio')
                    <div>
                        @foreach($field->options_array as $opt)
                            <label style="margin-right:16px; font-weight:normal; cursor:pointer;">
                                <input type="radio" name="{{ $field->field_name }}" value="{{ $opt }}" {{ old($field->field_name) == $opt ? 'checked' : '' }} {{ $field->is_required ? 'required' : '' }}>
                                {{ $opt }}
                            </label>
                        @endforeach
                    </div>
                @elseif($field->field_type === 'checkbox')
                    <div>
                        @php $oldVals = (array) old($field->field_name, []); @endphp
                        @foreach($field->options_array as $opt)
                            <label style="margin-right:16px; font-weight:normal; cursor:pointer;">
                                <input type="checkbox" name="{{ $field->field_name }}[]" value="{{ $opt }}" {{ in_array($opt, $oldVals) ? 'checked' : '' }}>
                                {{ $opt }}
                            </label>
                        @endforeach
                    </div>
                @else
                    <input type="{{ $field->field_type }}" name="{{ $field->field_name }}"
                           value="{{ old($field->field_name) }}"
                           placeholder="{{ $field->placeholder }}"
                           {{ $field->is_required ? 'required' : '' }}
                           style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
                @endif
            </div>
        @endforeach

        <button type="submit" style="background:#2563eb; color:#fff; border:0; padding:10px 22px; border-radius:6px; font-weight:600; cursor:pointer;">
            {{ $form->submit_label ?: 'Submit' }}
        </button>
    </form>
</div>

