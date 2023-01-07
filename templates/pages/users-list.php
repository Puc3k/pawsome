<main>
    <div class="ranking">
        <div class="container pb-3">
            <h1 class="text-center text-light py-4">Lista użytkowników</h1>
            <table class="table table-light table-striped table-hover align-middle fs-5">
                <thead>
                    <tr>
                        <th scope="col">Lp.</th>
                        <th scope="col">Avatar</th>
                        <th scope="col">Nazwa użytkownika</th>
                        <th scope="col">Adres e-mail</th>
                        <th scope="col">Rola</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($params['usersList'] ?? [] as $key => $value): ?>
                        <tr>
                            <th scope="row"><?= $key + 1 . "." ?></th>
                            <td><div class="avatar-img" style="background-image: url(<?="{$value['avatar']}" ?>);"></div></td>
                            <td><?="{$value['username']}" ?></td>
                            <td><?="{$value['email']}" ?></td>
                            <td><?="{$value['role']}" ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</main>