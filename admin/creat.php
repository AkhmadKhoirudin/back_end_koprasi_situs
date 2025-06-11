<?php
require '../koneksi.php';


// --- BACKEND ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    header("Content-Type: application/json");

    $action = $_POST["action"];
    $id = intval($_POST["id"] ?? 0);
    $kategori = $_POST["kategori"] ?? [];

    if (!is_array($kategori)) $kategori = [$kategori];
    $kategori = array_values(array_unique(array_map("trim", $kategori)));
    $kategori_json = json_encode($kategori);

    if ($action === "add" && $id) {
        $stmt = $conn->prepare("UPDATE content SET kategori=? WHERE id=?");
        $stmt->bind_param("si", $kategori_json, $id);
        $stmt->execute();
        echo json_encode(["message" => "Kategori ditambahkan pada ID $id"]);
        exit;
    }

    if ($action === "update" && $id) {
        $stmt = $conn->prepare("UPDATE content SET kategori=? WHERE id=?");
        $stmt->bind_param("si", $kategori_json, $id);
        $stmt->execute();
        echo json_encode(["message" => "Kategori diperbarui untuk ID $id"]);
        exit;
    }

    if ($action === "delete" && $id) {
        $res = $conn->query("SELECT kategori FROM content WHERE id=$id");
        if ($res && $row = $res->fetch_assoc()) {
            $old_kat = json_decode($row["kategori"], true);
            $new_kat = array_values(array_diff($old_kat, $kategori));
            $new_json = json_encode($new_kat);
            $stmt = $conn->prepare("UPDATE content SET kategori=? WHERE id=?");
            $stmt->bind_param("si", $new_json, $id);
            $stmt->execute();
            echo json_encode(["message" => "Kategori dihapus dari ID $id"]);
            exit;
        }
    }

    echo json_encode(["message" => "Aksi tidak valid"]);
    exit;
}
// --- AMBIL KATEGORI TERSEDIA ---
$kategoriTersedia = [];
$res = $conn->query("SELECT kategori FROM content WHERE kategori IS NOT NULL");
while ($row = $res->fetch_assoc()) {
    $data = json_decode($row["kategori"], true);
    if (is_array($data)) {
        foreach ($data as $kat) {
            $kategoriTersedia[] = trim($kat);
        }
    }
}
$kategoriTersedia = array_values(array_unique($kategoriTersedia));

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CKEditor 5 - Full demo with code</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    #container {
        max-width: 700px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 40px auto;
    }

    label {
        font-weight: bold;
        margin-top: 10px;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #editor {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        min-height: 200px;
        background: white;
    }

    #saveBtn {
        margin-top: 15px;
        width: 100%;
        padding: 10px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #saveBtn:hover {
        background-color: #0056b3;
    }
</style> -->
   <style>
    /* Struktur dasar halaman agar memenuhi layar */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    .container-fluid {
        height: 100%;
    }

    .row {
        height: 100%;
        margin: 0;
        display: flex;
        flex-wrap: nowrap;
    }

    /* Sidebar kiri */
    .sidebar {
        background: #f8f9fa;
        border-right: 1px solid #dee2e6;
        padding: 20px;
        min-width: 250px;
        overflow-y: auto;
    }

    /* Konten utama kanan */
    .main-content {
        flex: 1;
        padding: 20px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Area editor penuh tinggi */
    #editor-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    #editor {
        flex: 1;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        min-height: 300px;
        max-height: 100%;
        overflow-y: auto;
    }

    /* Styling langsung untuk CKEditor internal */
    .ck-editor__editable_inline {
        flex: 1 !important;
        min-height: 90vh !important;
        height: 100% !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 0.375rem 0.75rem !important;
        overflow-y: auto;
    }

    /* Preview thumbnail */
    #thumbnail-preview {
        display: none;
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }

        .sidebar {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
            min-height: auto;
        }

        .main-content {
            height: auto;
        }
    }
</style>

<style>
            .ck-source-editing-button, 
            button[data-cke-tooltip-text="Insert HTML"],
            button[data-cke-tooltip-text="Insert code block"],
            button[data-cke-tooltip-text="Export to PDF"],
            button[data-cke-tooltip-text="Export to Word"] {
                display: none !important;
            }
</style>

<style>
        body { font-family: Arial; margin: 20px; }
        .badge { background: #007bff; color: white; padding: 5px 10px; border-radius: 12px; margin: 3px; display: inline-block; cursor: pointer; }
        .badge span { margin-left: 5px; cursor: pointer; color: #ffc107; }
        .button { padding: 6px 12px; margin: 5px; }
        .kategori-box { border: 1px solid #ccc; padding: 10px; margin-top: 10px; }
        table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
      
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar Kiri (Judul & Thumbnail) -->
                <div class="col-md-3 sidebar">
                    <h4 class="mb-4">Form Input</h4>
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul:</label>
                        <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan judul">
                    </div>
                    
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Thumbnail:</label>
                        <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <img id="thumbnail-preview" src="#" alt="Preview Thumbnail" class="img-thumbnail">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan:</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="4" placeholder="Masukkan keterangan"></textarea>
                    </div>
                    
                    <!-- /////////////////////////////kategori////////////////////////////// -->
                    
                    <form id="kategoriForm" onsubmit="return handleAction()">
                       

                        <label><strong>Kategori (ketik lalu tekan Enter):</strong></label><br>
                        <input type="text" id="kategoriInput" onkeydown="if(event.key==='Enter'){event.preventDefault(); addKategori();}" placeholder="Misal: ekonomi"><br>

                        <div class="kategori-box">
                            <strong>Kategori Terpilih:</strong><br>
                            <div id="selectedKategori"></div>
                        </div>

                        <div class="kategori-box">
                            <strong>Kategori Tersedia:</strong><br>
                            <?php foreach ($kategoriTersedia as $kat): ?>
                                <span class="badge" onclick="selectKategori('<?php echo $kat; ?>')"><?php echo $kat; ?></span>
                            <?php endforeach; ?>
                        </div>

                        <br>
                       
                    </form>

                    <!-- //////////////////////////////////////////////// -->
                    
                    <div class="text-end mt-4">
                        <button id="saveBtn" class="btn btn-primary px-4">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </div>
                
                <!-- Konten Utama (Kanan: Editor Full Height) -->
                <div class="col-md-9 main-content">
                    <div id="editor-container">
                        <label for="isi" class="form-label">Isi:</label>
                        <div id="editor"></div>
                    </div>
                </div>
            </div>
        </div>
         <!-- Tambahkan SweetAlert2 untuk Popup -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 

        <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/super-build/ckeditor.js"></script>


<!-- bagaian -->
 <script>
    let selected = [];

    function setAction(act) {
        document.getElementById("actionField").value = act;
    }

    function selectKategori(kat) {
        if (!selected.includes(kat)) {
            selected.push(kat);
            renderSelected();
        }
    }

    function addKategori() {
        const input = document.getElementById("kategoriInput");
        const val = input.value.trim();
        if (val && !selected.includes(val)) {
            selected.push(val);
            renderSelected();
        }
        input.value = "";
    }

    function removeKategori(kat) {
        selected = selected.filter(k => k !== kat);
        renderSelected();
    }

    function renderSelected() {
        const container = document.getElementById("selectedKategori");
        container.innerHTML = '';
        selected.forEach(kat => {
            const badge = document.createElement("span");
            badge.className = "badge";
            badge.innerHTML = `${kat} <span onclick="removeKategori('${kat}')">&times;</span>`;
            container.appendChild(badge);
        });
    }

    function handleAction() {
        const form = document.getElementById("kategoriForm");

        // Hapus input kategori[] lama
        document.querySelectorAll("input[name='kategori[]']").forEach(e => e.remove());

        // Tambahkan input baru
        selected.forEach(kat => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "kategori[]";
            input.value = kat;
            form.appendChild(input);
        });

        const formData = new FormData(form);
        fetch(location.href, {
            method: "POST",
            body: formData
        }).then(res => res.json())
          .then(data => {
              alert(data.message || "Berhasil");
              location.reload();
          });

        return false;
    }
</script>


<!-- bagian -->
<script>
    function forceFullscreenEditor() {
    const editorElement = document.querySelector('.ck-editor__editable_inline');
    
    if (editorElement) {
        editorElement.style.position = 'fixed';
        editorElement.style.top = '0';
        editorElement.style.left = '0';
        editorElement.style.width = '100vw';
        editorElement.style.height = '100vh';
        editorElement.style.zIndex = '9999';
        editorElement.style.background = 'white';
        editorElement.style.overflow = 'auto';
    }
}

// Panggil fungsi untuk mengaktifkan full screen
forceFullscreenEditor();
</script>
<script>
    let editorInstance;

    // Inisialisasi CKEditor
    CKEDITOR.ClassicEditor.create(document.getElementById("editor"))
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    document.getElementById("saveBtn").addEventListener("click", function () {
        // Klik tombol Source Editing
        let sourceButton = document.querySelector(".ck-source-editing-button");
        if (sourceButton) {
            sourceButton.click(); // Aktifkan mode Source Editing
        }

        // Tunggu agar editor beralih ke mode Source
        setTimeout(() => {
            let sourceEditor = document.querySelector("textarea[aria-label='Source code editing area']");
            if (sourceEditor) {
                let editorData = sourceEditor.value; // Ambil data dari mode Source

                let formData = new FormData();
                formData.append("judul", document.getElementById("judul").value);
                formData.append("keterangan", document.getElementById("keterangan").value);
                formData.append("content", editorData);

                // Mengambil file gambar dan mengonversinya ke base64
                let fileInput = document.getElementById("gambar");
                if (fileInput.files.length > 0) {
                    let file = fileInput.files[0];
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        let base64String = e.target.result.split(",")[1]; // Ambil data base64 tanpa prefix
                        formData.append("gambar", base64String);

                        // Kirim data setelah gambar dikonversi ke base64
                        sendData(formData);
                    };

                    reader.readAsDataURL(file);
                } else {
                    formData.append("gambar", ""); // Jika tidak ada gambar
                    sendData(formData);
                }
            } else {
                alert("Gagal mengambil data dari editor.");
            }
        }, 500); // Tunggu 500ms agar mode Source aktif
    });

    function sendData(formData) {
    fetch("save.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: "Sukses!",
                text: data.message,
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke content.php setelah klik OK
                    window.location.href = data.redirect || 'content.php';
                }
            });
        } else {
            Swal.fire({
                title: "Gagal!",
                text: data.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menyimpan data",
            icon: "error",
            confirmButtonText: "OK"
        });
    });
    }
</script>

<script>
            // This sample still does not showcase all CKEditor 5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Changing the language of the interface requires loading the language file using the <script> tag.
                // language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                placeholder: 'Welcome to CKEditor 5!',
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                fontSize: {
                    options: [ 20,  22, 24, 26, 'default', 28, 30, 32 , 34],
                    supportAllValues: true
                },
                // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                // Be careful with enabling previews
                // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                htmlEmbed: {
                    showPreviews: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType
                    'MathType'
                ]
            });
</script>
</body>
</html>