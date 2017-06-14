<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
    
  
  document.onkeydown = trapRefresh;
   function trapRefresh()
   {
    if (event.keyCode == 116)
     {
      event.keyCode = 0; 
      event.cancelBubble = true; 
      event.returnValue = false;
      document.location.reload(1);
     }
   }  
    </script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
  <title>Web Application</title>
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Metamorphous" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="style.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://use.fontawesome.com/3f41b57f4d.js"></script>
</head>
<body>

<div class="container-fluid headerBox" style="padding-right: 0px; padding-left: 0px; overflow: hidden;">
  <div class="first-section">
    <!--  -->
  </div>
  <div class="middle">
  
    <h1 class="text-center text-uppercase headertext">UOS25 편의점 관리 시스템</h1>
    <ul class="list-inline socialIcons text-center">
      <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-flickr" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-google-plus-official" aria-hidden="true"></i></a></li>
    </ul>
  </div>

</div>

<div class="container-fluid ">
  <div class="events">
  <h2 class="text-center pricingheader">NOTICE <i class="fa fa-lightbulb-o" aria-hidden="true"></i></h2>
    <div class="row allEvent" >
      <div class="col-md-4">
        <div class="eventPart">
          <div class="event-header">
            <h2 class="text-center">오늘의 할일</h2>
          <!-- #<p class="text-center">140 <span>/DAY</span></p> -->
          </div>
          <div class="event-content">
            <ul>

              <li>1. 폐기 상품 처리</li>
              <li>2. 입고된 품목 배치</li>
              <li> .</li>
              <li> .</li>
              <li> .</li>
              <li> .</li>
            </ul>
            <a src="#contactus" class="btn btn-danger contact">바로가기</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="eventPart">
          <div class="event-header">
            <h2 class="text-center">금일 폐기 상품</h2>
          </div>
          <div class="event-content">
            <ul>
            <?php
                        include_once("./db.php");

                        function do_fetch($s)
                        {
                          while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
                          {
                            echo "<li >[ 품목 ]  ".$row['PROD_NAME']."　[ 수량 ]  ".$row['QTY']."개</li>";
                         
                          }
                        }

                        $query = "SELECT 
                        TO_CHAR(a.ENT_DATE,'yyyy/mm/dd'),
            b.PROD_NAME,
                        TO_CHAR(a.EXPDATE,'yyyy/mm/dd'),
            a.QTY
                        FROM EXP_DATE_MANAGEMENT a,PRODUCT b
            WHERE TO_CHAR(a.EXPDATE,'yyyymmdd')=TO_CHAR(sysdate,'yyyymmdd')
            AND a.PROD_NUM = b.PROD_NUM";
  
                        $s = oci_parse($conn,$query);
          
                        oci_execute($s);
                        do_fetch($s);

                    
                        ?>
            </ul>
            <a href="stock_expdate_manage.php" class="btn btn-danger contact">바로가기</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="eventPart">
          <div class="event-header">
            <h2 class="text-center">배터리 대여현황</h2>
          </div>
          <div class="event-content">
          <ul>
            <?php
            include_once("./db.php");

            
                        function do_fetch1($s)
                        {
                          while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
                          {
                            echo "<li >[ 관리 번호 ]  ".$row['MANAGEMENT_NUM']."　[ 반납예정일 ]  ".$row['TMP']."</li>";
                         
                          }
                        }

            $query = "SELECT management_num,device,to_char(rental_date,'yyyy/mm/dd') as tmp,to_char(rental_date+rental_period,'yyyy/mm/dd'),rental_period,phone_num,rental_price FROM BATTERY";
            $s = oci_parse($conn,$query);
            oci_execute($s);
            do_fetch1($s);

            oci_close($conn);
            ?>
            </ul>
            <a href="service_battery.php" class="btn btn-danger contact">바로가기</a>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="second-secton">
    <div class="row">
      <div class="col-md-5">
        <img src="images/sideimage.png">
      </div>
      <div class="col-md-7 ">
        <div class="content">
        <br>
        <br>
        <br>
        <h2>UOS25 Management System</h2>
        <p class="text-justify">기존 UOS25 자원관리시스템의 노후화로 인해 새롭게 고안되었으며, 주문반품, 판매관리, 품목관리, 재고관리, 자금관리, 결산관리, 직원관리, 부가서비스 크게 8가지 기능으로 구분되어 있습니다.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="continer-fluid">
  <div class="third-section">
     <div class="header_text">
      <h2 class="text-center">Our Services</h2>
      <p class="text-center">UOS25 Management System의 대표적인 기능입니다.</p>
     </div>

     <div class="items">
      <div class="row working-substance">
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-shopping-cart fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">주문반품</p>
          <p class="ites-content text-justify">주문내역, 반품내역 조회 및 해당 공급업체에 주문과 반품기능을 수행할 수 있습니다.</p>
        </div>
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-won fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">판매관리</p>
          <p class="ites-content text-justify">판매된 상품을 입력하고 판매내역, 환불내역 조회 및 해당 판매 환불기능을 제공하고 판매와 환불에 따른 영수증 조회기능을 제공합니다.</p>
        </div>
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-wrench fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">품목관리</p>
          <p class="ites-content text-justify">판매가능한 상품을 등록하고 각 판매상품 별 이벤트 상품을 등록합니다.</p>
        </div>
      </div>

      <div class="row working-substance">
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-inbox fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">재고관리</p>
          <p class="ites-content text-justify">물품의 입/출고 정보 등록 및 검색 기능과 유통기한이 지나거나 임박한 물품 리스트 제공합니다.</p>
        </div>
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-credit-card fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">자금관리</p>
          <p class="ites-content text-justify">UOS25의 자금 수입/지출 등록 및 조회기능을 제공합니다.</p>
        </div>
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-edit fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">결산관리</p>
          <p class="ites-content text-justify">금일 현금과 카드 구분에 따른 수익과 지출을 구분하여 조회 및 입력 후 결산기능을 제공합니다.</p>
        </div>
      </div>

      <div class="row working-substance">
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-users fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">직원관리</p>
          <p class="ites-content text-justify">직원 등록 및 삭제 기능, 직원 근무시간 입력 기능을 제공합니다./p>
        </div>
        <div class="col-md-4">
          <p class="icons"><i class="fa fa-cubes fa-fw" aria-hidden="true"></i></p>
          <p class="items-header">부가서비스</p>
          <p class="ites-content text-justify">택배서비스 입력 및 조회기능과 보조배터리 대여 및 조회기능을 제공합니다.</p>
        </div>
        <div class="col-md-4">
          <p class="icons"><i class="" aria-hidden="true"></i></p>
          <p class="items-header"></p>
          <p class="ites-content text-justify"></p>
        </div>
      </div>
     </div>

  </div>  
</div>






<div class="container-fluid">
    <div class="ourteam">
      <div class="teamheader">
        <h2 class="text-center">Our Member</h2>
        <p class="text-center">UOS 25의 성실하고 멋진 3명의 직원들입니다</p>
      </div>

      <div class="team-member">
        <div class="row allEvent">
          <div class="col-md-4">
            <div class="member">
                <img src="images/team/tong.png" class="member-avatar">
              <div class="member-info">
                <h3 class="text-center">Tongil Song</h3>
                <p class="text-center">Member</p>
                <ul class="list-inline">
                  <li><a href="http://www.facebook.com/tongtongil" target="myIframe"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="member">
                <img src="images/team/su.png" class="member-avatar">
              <div class="member-info">
                <h3 class="text-center">Suzin Oh</h3>
                <p class="text-center">Member</p>
                <ul class="list-inline">
                  <li><a href="http://www.facebook.com/oh.oh.oh.sj" target="myIframe"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="member">
            <br>
            <br>
            <br>
            <br><br><br><br><br>
                <img src="images/team/jun.png" class="member-avatar">
              <div class="member-info">
                <h3 class="text-center">juneui Lee</h3>
                <p class="text-center">Member</p>
                <ul class="list-inline">
                  <li><a href="https://www.facebook.com/wnsspdl" target="myIframe"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
</div>



<div class="footer">
  <div class="home-footer">
  <h2 class="text-center">UOS25 MANAGEMENT SYSTEM</h2>
    <div class="social-line">
           <ul class="list-inline">
        </ul>
    </div>
    <p class="text-center">All Rights Reserved By UOS25</p>
  </div>

</div>
<script type="text/javascript">
  function changeUrl(url)
  {
   document.getElementById("main_frame").src = url;
}

document.onkeydown = trapRefresh;
function trapRefresh()
{
    if (event.keyCode == 116)
    {
      event.keyCode = 0; 
      event.cancelBubble = true; 
      event.returnValue = false;
      document.getElementById("main_frame").contentDocument.location.reload(true);
  }
}  
</script>

</body>
</html>