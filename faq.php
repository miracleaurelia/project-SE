<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $faq_title = $row['faq_title'];
    $faq_banner = $row['faq_banner'];
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $faq_banner; ?>);">
    <div class="inner">
        <h3><?php echo $faq_title; ?></h3>
    </div>
</div>

<style>
    a:hover,a:focus{
    text-decoration: none;
    outline: none;
}
#accordion:before{
    content: "";
    width: 1px;
    height: 80%;
    background: #550527;
    position: absolute;
    top: 20px;
    left: 24px;
    bottom: 20px;
}
#accordion .panel{
    border: none;
    border-radius: 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    margin: 0 0 12px 50px;
    position: relative;
}
#accordion .panel:before{
    content: "";
    width: 2px;
    height: 100%;
    background: linear-gradient(to bottom, #688e26 0%,#ff816a 100%);
    position: absolute;
    top: 0;
    left: -2px;
}
#accordion .panel-heading{
    padding: 0;
    background: #fff;
    position: relative;
}
#accordion .panel-heading:before{
    content: "";
    width: 15px;
    height: 15px;
    border-radius: 50px;
    background: #fff;
    border: 1px solid #550527;
    position: absolute;
    top: 50%;
    left: -48px;
    transform: translateY(-50%);
}
#accordion .panel-title a{
    display: block;
    padding: 15px 55px 15px 30px;
    font-size: 20px;
    font-weight: 600;
    color: #550527;
    border: none;
    margin: 0;
    position: relative;
}
#accordion .panel-title a:before,
#accordion .panel-title a.collapsed:before{
    content: "+";
    font-family: "Font Awesome 4 Free";
    font-weight: 900;
    width: 25px;
    height: 25px;
    line-height: 25px;
    border-radius: 50%;
    font-size: 15px;
    font-weight: normal;
    color: #688e26;
    text-align: center;
    border: 1px solid #688e26;
    position: absolute;
    top: 50%;
    right: 25px;
    transform: translateY(-50%);
    transition: all 0.5s ease 0s;
}
#accordion .panel-title a:before{
    content: "-";
}
#accordion .panel-body{
    padding: 0 30px 15px;
    border: none;
    font-size: 14px;
    color: #305275;
    line-height: 28px;
}
.faq {
    margin-top: 2rem !important;
    margin-bottom: 2rem !important;
}
</style>
<div class="container faq m-auto">
    <div class="row d-flex justify-content-center m-auto">
        <div class="col-md-6">
            <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_faq");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                foreach ($result as $row) {
            ?>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading<?php echo $row['faq_id']; ?>">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $row['faq_id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $row['faq_id']; ?>">
                                Q: <?php echo $row['faq_title']; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?php echo $row['faq_id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $row['faq_id']; ?>">
                        <div class="panel-body">
                            <p>
                                <?php echo $row['faq_content']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>