<style type="text/css">
.pricingTable{
	text-align: center;
}
.pricingTable .pricingTable-header{
	padding: 30px 0;
	background: #4d4d4d;
	position: relative;
	transition: all 0.3s ease 0s;
}
.pricingTable:hover .pricingTable-header{
	background: #31b0d5;
}
.pricingTable .pricingTable-header:before,
.pricingTable .pricingTable-header:after{
	content: "";
	width: 16px;
	height: 16px;
	border-radius: 50%;
	border: 1px solid #d9d9d8;
	position: absolute;
	bottom: 12px;
}
.pricingTable .pricingTable-header:before{
	left: 40px;
}
.pricingTable .pricingTable-header:after{
	right: 40px;
}
.pricingTable .heading{
	font-size: 20px;
	color: #fff;
	text-transform: uppercase;
	letter-spacing: 2px;
	margin-top: 0;
}
.pricingTable .price-value{
	display: inline-block;
	position: relative;
	font-size: 55px;
	font-weight: bold;
	color: #31b0d5;
	transition: all 0.3s ease 0s;
}
.pricingTable:hover .price-value{
	color: #fff;
}
.pricingTable .currency{
	font-size: 30px;
	font-weight: bold;
	position: absolute;
	top: 6px;
	left: -19px;
}
.pricingTable .month{
	font-size: 16px;
	color: #fff;
	position: absolute;
	bottom: 15px;
	right: -30px;
	text-transform: uppercase;
}
.pricingTable .pricing-content{
	padding-top: 50px;
	background: #fff;
	position: relative;
}
.pricingTable .pricing-content:before,
.pricingTable .pricing-content:after{
	content: "";
	width: 16px;
	height: 16px;
	border-radius: 50%;
	border: 1px solid #7c7c7c;
	position: absolute;
	top: 12px;
}
.pricingTable .pricing-content:before{
	left: 40px;
}
.pricingTable .pricing-content:after{
	right: 40px;
}
.pricingTable .pricing-content ul{
	padding: 0 10px;
	margin: 0;
	list-style: none;
}
.pricingTable .pricing-content ul:before,
.pricingTable .pricing-content ul:after{
	content: "";
	width: 8px;
	height: 46px;
	border-radius: 3px;
	background: linear-gradient(to bottom,#818282 50%,#727373 50%);
	position: absolute;
	top: -22px;
	z-index: 1;
	box-shadow: 0 0 5px #707070;
	transition: all 0.3s ease 0s;
}
.pricingTable:hover .pricing-content ul:before,
.pricingTable:hover .pricing-content ul:after{
	background: linear-gradient(to bottom, #40c4db 50%, #34bacc 50%);
}
.pricingTable .pricing-content ul:before{
	left: 44px;
}
.pricingTable .pricing-content ul:after{
	right: 44px;
}
.pricingTable .pricing-content ul li{
	font-size: 15px;
	font-weight: bold;
	color: #777473;
	padding: 10px 0;
	border-bottom: 1px solid #d9d9d8;
}
.pricingTable .pricing-content ul li:last-child{
	border-bottom: none;
}
.pricingTable .read{
	display: inline-block;
	font-size: 16px;
	color: #fff;
	text-transform: uppercase;
	background: #d9d9d8;
	padding: 8px 25px;
	margin: 30px 0;
	transition: all 0.3s ease 0s;
}
.pricingTable .read:hover{
	text-decoration: none;
}
.pricingTable:hover .read{
	background: #31b0d5;
}
@media screen and (max-width: 990px){
	.pricingTable{ margin-bottom: 25px; }
}
</style>
<!--[if IE]>
	<script src="http://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<![endif]-->
<div class="demo" style="background:#c0bfbf;padding: 1em 0;">
	<div class="container">
		<div class="row">

			<!--<div class="col-md-offset-3 col-md-3 col-xs-12 col-sm-4">
				<div class="pricingTable" style="min-width: 220px;">
					<div class="pricingTable-header">
						<h3 class="heading">非实训类申报书</h3>
					</div>
					<div class="pricing-content">
						<ul>
							<li>适用范围：非实训类项目</li>

						</ul>
						<a onclick="opennew('普通类型申报书','project_apply',0)" class="read">提交申报书</a>
					</div>
				</div>
			</div>

			<div class="col-md-3 col-xs-12 col-sm-4">
				<div class="pricingTable" style="min-width: 220px;">
					<div class="pricingTable-header">
						<h3 class="heading">实训类申报书</h3>
					</div>
					<div class="pricing-content">
						<ul>
							<li>适用范围：实训类项目</li>

						</ul>
						<a onclick="opennew('实训类型申报书','project_sx_apply',0)" class="read">提交申报书</a>
					</div>
				</div>
			</div>-->

            <div class="col-md-3 col-xs-12 col-sm-3">
                <div class="pricingTable" style="min-width: 180px;">
                    <div class="pricingTable-header">
                        <h3 class="heading">普及月项目申报</h3>
                    </div>
                    <div class="pricing-content">
                        <ul>
                            <li>适用范围：普及月项目</li>
                        </ul>
                        <a onclick="opennew('普及月项目申报','project_skpjm',0)" class="read">提交申报书</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 col-sm-3">
                <div class="pricingTable" style="min-width: 180px;">
                    <div class="pricingTable-header">
                        <h3 class="heading">研究基地年度项目申报</h3>
                    </div>
                    <div class="pricing-content">
                        <ul>
                            <li>适用范围：研究基地年度项目申报</li>
                        </ul>
                        <a onclick="opennew('社科研究基地年度项目申报','project_researchbase',0)" class="read">提交申报书</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 col-sm-3">
                <div class="pricingTable" style="min-width: 180px;">
                    <div class="pricingTable-header">
                        <h3 class="heading">常态化科普项目申报</h3>
                    </div>
                    <div class="pricing-content">
                        <ul>
                            <li>适用范围：常态化科普项目申报</li>
                        </ul>
                        <a onclick="opennew('社科常态化科普项目申报','project_skcth',0)" class="read">提交申报书</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 col-sm-3">
                <div class="pricingTable" style="min-width: 180px;">
                    <div class="pricingTable-header">
                        <h3 class="heading">社科课题申报</h3>
                    </div>
                    <div class="pricing-content">
                        <ul>
                            <li>适用范围：社科课题申报</li>
                        </ul>
                        <a onclick="opennew('社科课题申报','project_coursetask',0)" class="read">提交申报书</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-12 col-sm-3" style="margin-top: 20px;">
                <div class="pricingTable" style="min-width: 180px;">
                    <div class="pricingTable-header">
                        <h3 class="heading">社科后期认定申报</h3>
                    </div>
                    <div class="pricing-content">
                        <ul>
                            <li>适用范围：社科后期认定申报</li>
                        </ul>
                        <a onclick="opennew('社科后期认定申报','project_laterdeclare',0)" class="read">提交申报书</a>
                    </div>
                </div>
            </div>

		</div>
	</div>
</div>

