<?php
require_once '../includes/session_check.php';
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

include('../includes/admin_header.php');

$id_livre = $_GET['id_livre']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$book = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $genre = $_POST['genre'];
    $annee_publication = $_POST['annee_publication'];
    $stock = $_POST['stock'];

    $image_url = $book['image_url']; 
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_temp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            $image_new_name = 'book_' . time() . '.' . $image_extension;
            $image_path = '../uploads/' . $image_new_name;
            move_uploaded_file($image_temp, $image_path);
            $image_url = $image_path;
        } else {
            echo "<script>alert('Image non valide. Veuillez télécharger un fichier avec une extension valide.');</script>";
        }
    }

    $stmt = $pdo->prepare("UPDATE livres SET titre = ?, auteur = ?, genre = ?, annee_publication = ?, stock = ?, image_url = ? WHERE id_livre = ?");
    $stmt->execute([$titre, $auteur, $genre, $annee_publication, $stock, $image_url, $id_livre]);

    echo "<script>alert('Livre mis à jour avec succès!');</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Livre</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .popupBox {
            position: fixed;
            top: 55%;
            left: 60%;
            transform: translate(-50%, -50%);
            width: 600px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 1000;
        }

        .popupBox h2 {
            text-align: center;
            color: #333;
        }

        .popupBox form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .popupBox label {
            font-weight: 500;
        }

        .popupBox input, .popupBox button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .popupBox button {
            background: #887fff;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .popupBox button:hover {
            background: #685bff;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: #f3f3f3 url('https://cdn-icons-png.flaticon.com/512/1828/1828778.png') no-repeat center;
            background-size: 15px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="popupBox">
        <div class="close" onclick="window.history.back();"></div>
        <h2>Modifier Livre</h2>
        <form action="update_book.php?id_livre=<?php echo $book['id_livre']; ?>" method="POST" enctype="multipart/form-data">
            <label for="titre">Titre</label>
            <input type="text" name="titre" value="<?php echo htmlspecialchars($book['titre']); ?>" required>

            <label for="auteur">Auteur</label>
            <input type="text" name="auteur" value="<?php echo htmlspecialchars($book['auteur']); ?>" required>

            <label for="genre">Genre</label>
            <input type="text" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required>

            <label for="annee_publication">Année de publication</label>
            <input type="number" name="annee_publication" value="<?php echo htmlspecialchars($book['annee_publication']); ?>" required>

            <label for="stock">Stock</label>
            <input type="number" name="stock" value="<?php echo htmlspecialchars($book['stock']); ?>" required>

            <label for="image">Image du livre (facultatif)</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
