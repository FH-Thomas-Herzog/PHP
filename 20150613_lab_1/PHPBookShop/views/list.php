<?php
$categories = DataManager::getCategories();
$categoryId = isset($_REQUEST['categoryId']) ? ((int)$_REQUEST['categoryId']) : null;
$books = isset($categoryId) ? DataManager::getBooksForCategory($categoryId) : null;
?>

    <div class="page-header">List of books by category</div>

<?php
require('partial/header.php');
?>

    <ul class="nav nav-tabs">
        <?php foreach ($categories AS $cat) : ?>
            <li
                <?php $categoryId == $cat->getId() ? print (' class="active"') : null ?>
                >
                <a href="
            <?php echo $_SERVER['PHP_SELF'] . "?view=list&categoryId=" . $cat->getId(); ?>
        ">
                    <?php echo Util::escape($cat->getName()) ?></a></li>
        <?php endforeach; ?>
    </ul>

<?php
if (isset($books)) {
    if (sizeof($books) > 0) {
        require('partial/booklist.php');
    } else {
        echo "<div class='alert alert-warning'> No books in this category</div>";
    }
}
?>
<?php
require('partial/footer.php');
?>