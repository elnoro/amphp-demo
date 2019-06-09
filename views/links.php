<?php /** @var string[] $links */ ?>

<h2>Welcome to Quickshare! Share your link here!</h2>
<section>
<h3>Shared link: </h3>
<?php foreach ($links as $link) { ?>
    <form method="post">
        <input type="hidden" name="url" value="<?=$link?>"/><br>
        <input type="hidden" name="type" value="delete"/><br>
        <input type="submit" value="Delete">
        <a href="<?=$link ?>"><?=$link?></a>
    </form>
    <br>
<?php } ?>
</section>

<section>
    <h3>Add a link</h3>
    <form method="post">
        <label for="newUrl">Url: </label>
        <input type="url" name="url" placeholder="https://google.com"/><br>
        <input type="submit" value="Send">
    </form>
</section>
