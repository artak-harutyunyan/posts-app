<?php
require_once 'my.php';

$page_title = 'My posts';
require_once __DIR__ . '/../../includes/header.php';
?>
<h1>My posts</h1>

<?php if (count($posts) === 0): ?>
    <p>You have no posts yet.</p>
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <div class="post">
        <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>

        <?php if ((int) $post['is_blocked'] === 1): ?>
            <p class="post-blocked">Blocked by admin</p>
        <?php endif; ?>

       

       <p class="post-text"><?= htmlspecialchars(mb_substr($post['content'], 0, 150, 'UTF-8')) ?></p>
         <?php if (!empty($post['image_path'])): ?>
           <img class="post-img" src="/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="" >
        <?php endif; ?>
       <small class="post-date"><time datetime="<?= htmlspecialchars($post['created_at']) ?>">
                <?= date('d M Y', strtotime($post['created_at'])) ?>
            </time></small>
        <br>
        <div style="display: flex; gap: 10px;">
            <a href="/posts/update/form.php?id=<?= urlencode($post['id']) ?>"><button>Edit</button></a>
            <form action="/posts/delete/delete.php" method="POST">
                <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
               <button type="submit" class="btn-danger">Delete</button>
            </form>
        </div>
        
    </div>
<?php endforeach; ?>