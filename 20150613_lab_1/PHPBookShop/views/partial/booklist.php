<table class="table">
    <thead>
    <tr>
        <th>
            Title
        </th>
        <th>
            Author
        </th>
        <th>
            Price
        </th>
        <th>
            <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($books as $book):

        $inCard = ShoppingCard::contains($book->getId());

        ?>
        <tr <?php if($inCard) { print 'class="inCart"'; } ?>>
            <td><strong>
                    <?php echo Util::escape($book->getTitle()); ?>
                </strong>
            </td>
            <td>
                <?php echo Util::escape($book->getAuthor()); ?>
            </td>
            <td>
                <?php echo Util::escape($book->getPrice()); ?>
            </td>
            <td class="add-remove">
                <form action="<?php echo Util::action('addToCart', array(
                    'bookId' => $book->getId()
                ))?>" method="post" >
                    <input class="btn btn-default btn-xs btn-info" type="submit"/>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
