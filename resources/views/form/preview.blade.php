<x-app-layout>
    <div class="container mt-6">
       

        <div class="flex-grow-1 px-4" id="content">
            <div class="container my-4">
                <div class="row">
                    <div class="col-md-6 float-start">
                         <h3 class="mb-4">{{ $form->title }}</h3>
                    </div>
    
                    <div class="col-md-6" style="width: 508px;">
                        <a href="{{ url('/forms/'.$form->slug) }}" id="saveFormBtn" type="submit" class="btn btn-success btn-sm float-end me-4">Share</a>
                    </div>
                </div>
            </div>

            <div id="form-canvas" class="p-3 border rounded bg-white mx-auto"
                style="min-height:600px; width:950px; border:2px dashed #bbb;">
                @foreach ($schema['fields'] as $field)
                    <div class="mb-3">
                        <label class="form-label">{{ $field['label'] }}</label>

                        {{-- Text, Email, Number, Date, Phone, Website --}}
                        @if (in_array($field['type'], ['text', 'email', 'number', 'date', 'tel', 'url']))
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                @if (!empty($field['required'])) required @endif>

                            {{-- Textarea --}}
                        @elseif($field['type'] === 'textarea')
                            <textarea name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}"></textarea>

                            {{-- Select (Dropdown) --}}
                        @elseif($field['type'] === 'select')
                            <select name="{{ $field['name'] }}" class="form-control">
                                @foreach ($field['options'] ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>

                            {{-- Radio buttons --}}
                        @elseif($field['type'] === 'radio')
                            <div>
                                @foreach ($field['options'] ?? [] as $option)
                                    <div class="form-check">
                                        <input type="radio" name="{{ $field['name'] }}" value="{{ $option }}"
                                            class="form-check-input">
                                        <label class="form-check-label">{{ $option }}</label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Checkboxes --}}
                        @elseif($field['type'] === 'checkbox')
                            <div>
                                @foreach ($field['options'] ?? [] as $option)
                                    <div class="form-check">
                                        <input type="checkbox" name="{{ $field['name'] }}[]"
                                            value="{{ $option }}" class="form-check-input">
                                        <label class="form-check-label">{{ $option }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    </div>
</x-app-layout>
