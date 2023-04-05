<?php 
    const ERROR_REQUIRED = 'Veuillez renseigner une todo';
    const ERROR_TOO_SHORT = 'Veuillez entrer au moins 5 caractères';
    
    $filename = __DIR__."/data/todo.json";
    $error = ''; 
    $todo =''; 
    $todos =[];

    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        $todos = json_decode($data, true) ?? []; 
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $todo = $_POST ['todo'] ?? ''; 

        if (!$todo) {
            $error = ERROR_REQUIRED;
        } elseif (mb_strlen($todo) < 5) {
            $error = ERROR_TOO_SHORT;
    }
    if (!$error){

        $todos = [...$todos, [
            'name' => $todo,
            'done' => false, 
            'id' => time()
        ]];
        file_put_contents($filename, json_encode($todos));
        header('location:/');




    }
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head.php' ?>
    <title>Todo</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="todo-contenair">
                <h1>Ma todo</h1>
                <form action="/" method="POST" class="todo-form">
                    <input  name="todo" type="text" value=<?= $todo ?>>
                    <button class="btn btn-primary">Ajouter</button>

                </form>
                <?php if($error) : ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>
                <ul class="todo-list">
                <?php foreach ($todos as $t) : ?>
                        <li class="todo-item <?= $t['done'] ? 'low-opacity' : '' ?>">
                            <span class="todo-name"><?= $t['name'] ?></span>
                            <a href="/edit-todo.php?id=<?= $t['id'] ?>">
                                <button class="btn btn-primary btn-small"><?= $t['done'] ? 'Annuler' : 'Valider' ?></button>
                            </a>
                            <a href="/remove-todo.php?id=<?= $t['id'] ?>">
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </a>
                            
                        </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
    
</body>

</html>