<?php
require '../koneksi.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID tidak valid.");
}

$query = $conn->prepare("SELECT judul, keterangan, isi, gambar FROM content WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Data tidak ditemukan.");
}

$data = $result->fetch_assoc();

// Gunakan langsung tanpa base64_encode karena data sudah dalam format Base64
$gambarBase64 = !empty($data['gambar']) ? 'data:image/png;base64,' . $data['gambar'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Konten</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/super-build/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .ck-source-editing-button, 
        button[data-cke-tooltip-text="Insert HTML"],
        button[data-cke-tooltip-text="Insert code block"],
        button[data-cke-tooltip-text="Export to PDF"],
        button[data-cke-tooltip-text="Export to Word"] {
            display: none !important;
        }
    </style>

</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Konten</h2>
        <form id="contentForm" method="POST" enctype="multipart/form-data" action="update.php">
            <input type="hidden" name="id" value="<?= $id ?>">

            <label for="judul">Judul:</label>
            <input type="text" id="judul" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>">

            <label for="gambar">Thumbnail:</label>
            <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
            
            <?php if (!empty($gambarBase64)): ?>
                <img src="<?= $gambarBase64 ?>" alt="Thumbnail" class="img-thumbnail mt-2" width="200">
            <?php endif; ?><br>

            <label for="keterangan">Keterangan:</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($data['keterangan']) ?></textarea>

            <label for="isi">Isi:</label>
            <textarea id="editor" name="isi"><?= htmlspecialchars($data['isi']) ?></textarea>
            
            <button type="submit" id="saveBtn" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>


    <script>
      document.getElementById("saveBtn").addEventListener("click", function (e) {
    e.preventDefault(); // Mencegah form langsung submit

    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menyimpan perubahan?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, Simpan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // console.log("Tombol Simpan diklik!");

            let sourceButton = document.querySelector(".ck-source-editing-button");
            if (sourceButton) {
                console.log("Tombol Source ditemukan!");
                if (!sourceButton.classList.contains("ck-on")) {
                    sourceButton.click(); // Aktifkan mode Source Editing jika belum aktif
                    // console.log("Mode Source diaktifkan!");
                } else {
                    console.log("Mode Source sudah aktif!");
                }
            } else {
                console.error("Tombol Source tidak ditemukan!");
            }

            setTimeout(() => {
                let sourceEditor = document.querySelector("textarea[aria-label='Source code editing area']");
                if (sourceEditor) {
                    console.log("Editor Source ditemukan!");
                    let editorData = sourceEditor.value; 
                    // console.log("Data dari editor:", editorData);

                    let formData = new FormData();
                    formData.append("id", document.querySelector("input[name='id']").value);
                    formData.append("judul", document.getElementById("judul").value);
                    formData.append("keterangan", document.getElementById("keterangan").value);
                    formData.append("isi", editorData); // Memasukkan isi editor ke formData

                    let fileInput = document.getElementById("gambar");
                    if (fileInput.files.length > 0) {
                        let file = fileInput.files[0];
                        let reader = new FileReader();

                        reader.onload = function (e) {
                            let base64String = e.target.result.split(",")[1];
                            formData.append("gambar", base64String);
                            console.log("Gambar dikonversi ke base64!");

                            sendData(formData);
                        };

                        reader.readAsDataURL(file);
                    } else {
                        formData.append("gambar", "");
                        console.log("Tidak ada gambar yang dipilih!");
                        sendData(formData);
                    }
                } else {
                    console.error("Editor Source tidak ditemukan!");
                }
            }, 500);
        }
    });
});

function sendData(formData) {
    console.log("Mengirim data ke server...");

    fetch("update.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Mengubah response ke JSON
    .then(data => {
        // console.log("Respon dari server:", data);
        if (data.success) {
            Swal.fire({
                title: "Berhasil!",
                text: "Data berhasil diperbarui!",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                location.reload(); // Refresh halaman setelah sukses
            });
        } else {
            Swal.fire({
                title: "Error!",
                text: data.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    })
    .catch(error => {
        console.error("Error saat mengirim data:", error);
        Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menyimpan data!",
            icon: "error",
            confirmButtonText: "OK"
        });
    });
}

// Inisialisasi CKEditor
let editorInstance;
CKEDITOR.ClassicEditor.create(document.getElementById("editor"))
    .then(editor => {
        editorInstance = editor;
    })
    .catch(error => {
        console.error("Error saat menginisialisasi CKEditor:", error);
    });
    </script>


    <script>
        CKEDITOR.ClassicEditor.create(document.getElementById("editor"))
            .then(editor => {
                editor.setData(`<?= htmlspecialchars($data['isi']) ?>`);
            })
            .catch(error => console.error(error));
    </script>

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
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
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

