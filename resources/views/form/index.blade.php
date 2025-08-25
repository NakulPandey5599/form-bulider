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
    <div class="flex-grow-1 p-4" id="content">
        <div class="container my-4">
            <h5 class="mb-3">Form</h5>
            <div id="form-canvas"
                class="p-3 border rounded bg-white"
                style="min-height:600px; width:950px; border:2px dashed #bbb;">
            </div> 
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    let currentFieldType = null; // store field type being dragged

    // Toolbox (drag only, clone mode)
    new Sortable(document.getElementById('toolbox'), {
        group: { name: 'shared', pull: 'clone', put: false },
        sort: false,
        animation: 150
    });

    // Form Canvas (drop zone)
    new Sortable(document.getElementById('form-canvas'), {
        group: 'shared',
        animation: 150,

        onAdd: function (evt) {
            let fieldType = evt.item.id;

            let fieldHtml = '';
            switch(fieldType) {
                case "name_card":
                    fieldHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter your name">
                        </div>`;
                    break;
                case "address_card":
                    fieldHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <textarea class="form-control" name="address" placeholder="Enter your address"></textarea>
                        </div>`;
                    break;
                case "phone_card":
                    fieldHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="tel" class="form-control" name="phone" placeholder="Enter your phone">
                        </div>`;
                    break;
                case "email_card":
                    fieldHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email">
                        </div>`;
                    break;
                case "website_card":
                    fieldHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold">Website</label>
                            <input type="url" class="form-control" name="website" placeholder="Enter your website">
                        </div>`;
                    break;
            }

            // Replace the cloned card with actual input field
            evt.item.outerHTML = fieldHtml;
        }
    });
</script>

</x-app-layout>
