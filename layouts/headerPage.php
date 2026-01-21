<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link rel="stylesheet" href="css/stylee.css"> -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>
        <?= isset($page_title) ? $page_title : 'SparepartME' ?>
    </title>
    <link rel="icon" type="image/png" href="img/logo.jpg">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?= $page_css ?? '' ?>">
    <?php if (isset($page_css_responsive)) : ?>
        <link rel="stylesheet" href="<?= $page_css_responsive ?>">
    <?php endif; ?>
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>