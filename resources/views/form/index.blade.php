<x-app-layout>
    <div class="d-flex flex-grow-1">
        <!-- Sidebar -->
        <div class="bg-light border-end" id="sidebar" style="width: 350px; min-height: 100vh;">
            <div class="p-3">
                <h5 class="fw-bold">Builder</h5>
                <hr>

                <div class="container">
                    <h5 class="mt-4 mb-3">Basic Info</h5>
                    <div class="row g-3" id="toolbox">
                        <div class="col-4" id="name_card">
                            <div class="box-card" data-field="name_card">
                                <i class="bi bi-person box-icon"></i> Name
                            </div>
                        </div>
                        <div class="col-4" id="address_card">
                            <div class="box-card" data-field="address_card">
                                <i class="bi bi-journal-text box-icon"></i> Address
                            </div>
                        </div>
                        <div class="col-4" id="phone_card">
                            <div class="box-card" data-field="phone_card">
                                <i class="bi bi-telephone box-icon"></i> Phone
                            </div>
                        </div>
                        <div class="col-4" id="email_card">
                            <div class="box-card" data-field="email_card">
                                <i class="bi bi-envelope box-icon"></i> Email
                            </div>
                        </div>
                        <div class="col-4" id="website_card">
                            <div class="box-card" data-field="website_card">
                                <i class="bi bi-globe box-icon"></i> Website
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 px-4" id="content">
            <div class="container my-4">
                <div class="row">
                  <div class="col-md-6 float-start">

                    <h5 class="mb-3">Form</h5>
                  </div>
                  <div class="col-md-6">

                    <button id="saveFormBtn" type="submit" class="btn btn-success btn-sm float-end me-4">Save Form</button>
                  </div>
                </div>
                <div class="mt-4">
                    <input id="form_title" class="form-control mb-2" placeholder="Form Name">
                </div>

                <meta name="csrf-token" content="{{ csrf_token() }}">

                <div id="form-canvas" class="p-3 border rounded bg-white"
                    style="min-height:600px; width:950px; border:2px dashed #bbb;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // small counter to generate unique names
        let fieldCounter = 0;

        // Helper to slugify names
        function slugify(s) {
            return s.toString().toLowerCase().replace(/\s+/g, '_').replace(/[^\w\-]+/g, '');
        }

        // existing Sortable for toolbox (unchanged)
        new Sortable(document.getElementById('toolbox'), {
            group: {
                name: 'fields',
                pull: 'clone',
                put: false
            },
            sort: false,
            animation: 150,
            fallbackOnBody: true
        });

        // Canvas Sortable with improved onAdd
        new Sortable(document.getElementById('form-canvas'), {
            group: 'fields',
            animation: 150,
            fallbackOnBody: true,

            onAdd: function(evt) {
                const cloned = evt.item; // the clone dropped
                let fieldType = cloned.id || cloned.getAttribute('data-field') || '';

                // try inside element if needed
                if (!fieldType) {
                    const inner = cloned.querySelector('[data-field]');
                    if (inner) fieldType = inner.getAttribute('data-field');
                }

                if (!fieldType) {
                    cloned.remove();
                    console.warn('Unknown dragged item; removed clone.');
                    return;
                }

                fieldCounter++;
                // create a unique name for the field (avoid [] arrays)
                const uniqueName = `${slugify(fieldType)}_${Date.now()}_${fieldCounter}`;

                // default label/title from the toolbox text
                const labelText = cloned.textContent.trim() || fieldType;

                // Build markup using uniqueName and store meta attributes for serialization
                let fieldHtml = '';
                switch (fieldType) {
                    case 'name_card':
                        fieldHtml = `
            <div class="form-group dropped-field" data-field-type="text" data-field-name="${uniqueName}">
              <div class="mb-3">
                <label class="form-label fw-bold">${labelText}</label>
                <input type="text" class="form-control" name="${uniqueName}" placeholder="Enter ${labelText}">
              </div>
            </div>`;
                        break;

                    case 'address_card':
                        fieldHtml = `
            <div class="form-group dropped-field" data-field-type="textarea" data-field-name="${uniqueName}">
              <div class="mb-3">
                <label class="form-label fw-bold">${labelText}</label>
                <textarea class="form-control" name="${uniqueName}" placeholder="Enter ${labelText}"></textarea>
              </div>
            </div>`;
                        break;

                    case 'phone_card':
                        fieldHtml = `
            <div class="form-group dropped-field" data-field-type="tel" data-field-name="${uniqueName}">
              <div class="mb-3">
                <label class="form-label fw-bold">${labelText}</label>
                <input type="tel" class="form-control" name="${uniqueName}" placeholder="Enter ${labelText}">
              </div>
            </div>`;
                        break;

                    case 'email_card':
                        fieldHtml = `
            <div class="form-group dropped-field" data-field-type="email" data-field-name="${uniqueName}">
              <div class="mb-3">
                <label class="form-label fw-bold">${labelText}</label>
                <input type="email" class="form-control" name="${uniqueName}" placeholder="Enter ${labelText}">
              </div>
            </div>`;
                        break;

                    case 'website_card':
                        fieldHtml = `
            <div class="form-group dropped-field" data-field-type="url" data-field-name="${uniqueName}">
              <div class="mb-3">
                <label class="form-label fw-bold">${labelText}</label>
                <input type="url" class="form-control" name="${uniqueName}" placeholder="Enter ${labelText}">
              </div>
            </div>`;
                        break;

                    default:
                        cloned.remove();
                        console.warn('Unhandled field type:', fieldType);
                        return;
                }

                // Replace clone with final field node
                const temp = document.createElement('div');
                temp.innerHTML = fieldHtml.trim();
                const newNode = temp.firstElementChild;
                cloned.parentNode.replaceChild(newNode, cloned);
                const firstInput = newNode.querySelector('input, textarea');
                if (firstInput) firstInput.focus();
            }
        });

        // SERIALIZE the canvas into a JSON schema
        function serializeFormCanvas() {
            const fields = [];
            document.querySelectorAll('#form-canvas .dropped-field').forEach(el => {
                const type = el.dataset.fieldType || 'text';
                const name = el.dataset.fieldName || (el.querySelector('input,textarea,select')?.name ??
                    `field_${Math.random().toString(36).slice(2)}`);
                const label = el.querySelector('label')?.textContent?.trim() ?? name;
                const input = el.querySelector('input,textarea,select');

                const placeholder = input?.getAttribute('placeholder') || null;

                const field = {
                    type,
                    name,
                    label,
                    placeholder,
                    required: false, // builder can add UI later to toggle required/validation
                    validation: null
                };

                // if select/checkbox/radio: collect options (example only)
                if (type === 'select' || type === 'radio' || type === 'checkbox') {
                    const options = [];
                    el.querySelectorAll('option, input[type="radio"], input[type="checkbox"]').forEach(opt => {
                        if (opt.value) options.push(opt.value);
                    });
                    field.options = options;
                }

                fields.push(field);
            });

            return {
                fields
            };
        }

        // Save form: send schema + title to server
        document.getElementById('saveFormBtn').addEventListener('click', async function() {
            const title = document.getElementById('form_title').value;

            //check if title is empty
           
            const schema = serializeFormCanvas();

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (title === null || title === undefined || title.trim() === '') {
              
            } else {
              // The title has a value
              console.log('The title has a value: ' + title);
            }
            
            try {
                const res = await fetch("{{ route('form.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title,
                        slug: null,
                        schema
                    })
                });

                const data = await res.json();
                if (!res.ok) throw data;
                alert('Form saved: ' + data.form.slug);
                // optionally redirect to edit page: window.location = `/admin/forms/${data.form.id}/edit`;
            } catch (err) {
                console.log(err);
                alert('Save failed. Check console.');
            }
        });
    </script>



</x-app-layout>
