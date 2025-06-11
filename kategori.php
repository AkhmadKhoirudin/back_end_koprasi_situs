<?php
// --- KONEKSI DATABASE ---
$conn = new mysqli("localhost", "root", "", "cms");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

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
<html>
<head>
    <title>Manajemen Kategori</title>
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

<h2>Manajemen Kategori Konten</h2>

<form id="kategoriForm" onsubmit="return handleAction()">
    <label><strong>ID Konten:</strong></label><br>
    <input type="number" name="id" required><br><br>

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
    <input type="hidden" name="action" id="actionField">
    <button type="submit" class="button" onclick="setAction('add')">Tambah</button>
    <button type="submit" class="button" onclick="setAction('update')">Update</button>
    <button type="submit" class="button" onclick="setAction('delete')">Hapus</button>
</form>

<!-- Tabel Data -->
<h3>Data Konten</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Kategori</th>
    </tr>
    <?php
    $result = $conn->query("SELECT id, judul, kategori FROM content ORDER BY id DESC LIMIT 10");
    while ($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row["id"] ?></td>
        <td><?= htmlspecialchars($row["judul"]) ?></td>
        <td><?= htmlspecialchars($row["kategori"]) ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- SCRIPT -->
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

</body>
</html>
