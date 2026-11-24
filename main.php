<?php
require_once __DIR__ . '/posts/read.php';

$page_title = 'Home';
require_once __DIR__ . '/includes/header.php';
?>
<h1>All posts</h1>

<?php if (count($posts) === 0): ?>
    <p>There are no posts yet.</p>
<?php endif; ?>

<?php foreach ($posts as $post): ?>

    <div class="post">
        <h3 class="post-author"><?= htmlspecialchars($post['username']) ?></h3>
        <br>
        <hr>
        <h4 class="post-title"><?= htmlspecialchars($post['title']) ?></h4>
      
        <p class="post-text">
            <?= htmlspecialchars(mb_substr($post['content'], 0, 200, 'UTF-8')) ?>
            <?= mb_strlen($post['content'], 'UTF-8') > 200 ? '...' : '' ?>
        </p>
          <?php if (!empty($post['image_path'])): ?>
            <img class="post-img" src="/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="" ">
        <?php endif; ?>
      <small class="post-date">
            
            <?= htmlspecialchars(date('d M y', strtotime($post['created_at']))) ?>
        </small><br>
        
        
        <?php if ((int) $post['user_id'] === (int) $user['id']): ?>
            <a href="/posts/update/form.php?id=<?= (int) $post['id'] ?>"><button type="button">Edit</button></a>
        <?php endif; ?>
    </div>
    
<?php 
endforeach; ?>