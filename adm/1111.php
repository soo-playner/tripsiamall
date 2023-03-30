<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Organization Chart Plugin</title>
  <link rel="icon" href="img/logo.png">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/jquery.orgchart.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style type="text/css">
    .orgchart { background: white; }
  </style>
</head>
<body>
  <div id="chart-container"></div>
		<ul id="org" style="display:none"  >
			<li>
				0.11  [0-449] 최고관리자 
				<ul>
					<li>1.1111  [1-448] 본사 
						<ul>
							<li>3.111111  [2-1] 이이이 
							</li>
							<li>4.111112  [2-1] 이순신 
							</li>
							<li>5.111113  [2-1] 홍현주 
							</li>
							<li>6.111114  [2-1] 정명순 
							</li>
							<li>7.111115  [2-1] 민향식 
							</li>
							<li>8.111116  [2-4] 최수성 
								<ul>
									<li>10.11111611  [3-1] 이애남 
									</li>
									<li>11.11111612  [3-1] 최옥분 
									</li>
									<li>12.11111613  [3-1] 김민아 
									</li>
								</ul>
							</li>
							<li>13.111117  [2-1] 최양례 
							</li>
							<li>14.111118  [2-1] 이귀남 
							</li>
							<li>15.111119  [2-1] 강병원 
							</li>
							<li>16.111120  [2-1] 정인숙 
							</li>
							<li>17.111121  [2-5] 정용순 
								<ul>
									<li>19.11112111  [3-1] 김일례 
									</li>
									<li>20.11112112  [3-1] 장병도 
									</li>
									<li>21.11112113  [3-2] 김옥순B 
										<ul>
											<li>23.1111211311  [4-1] 강필원 
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li>24.111122  [2-1] 유각상 
							</li>
							<li>25.111123  [2-1] 김인숙 
							</li>
							<li>26.111124  [2-1] 김기현 
							</li>
							<li>27.111125  [2-2] 김주순 
								<ul>
									<li>29.11112511  [3-1] 정숙자 
									</li>
								</ul>
							</li>
							<li>30.111126  [2-53] 석인수 
								<ul>
									<li>32.11112611  [3-52] 최연숙A 
										<ul>
											<li>34.1111261111  [4-1] 김복희 
											</li>
											<li>35.1111261112  [4-1] 원영호 
											</li>
											<li>36.1111261113  [4-1] 박윤희 
											</li>
											<li>37.1111261114  [4-1] 박명주 
											</li>
											<li>38.1111261115  [4-1] 강명철 
											</li>
											<li>39.1111261116  [4-1] 박선희 
											</li>
											<li>40.1111261117  [4-1] 석미령 
											</li>
											<li>41.1111261118  [4-2] 박복근 
												<ul>
													<li>43.111126111811  [5-1] 김요순 
													</li>
												</ul>
											</li>
											<li>44.1111261119  [4-1] 윤금엽 
											</li>
											<li>45.1111261120  [4-1] 박경미 
											</li>
											<li>46.1111261121  [4-1] 박연옥 
											</li>
											<li>47.1111261122  [4-1] 김용화 
											</li>
											<li>48.1111261123  [4-3] 노성녀 
												<ul>
													<li>50.111126112311  [5-1] 신영철 
												</ul>
											</li>
											<li>51.1111261124  [4-1] 박영래 
										</ul>
									</li>
								</ul>
							</li>
							<li>52.111127  [2-1] 정옥삼 
							</li>		
						</ul>

					</li>
				</ul>
			</li>


	</ul>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery.orgchart.min.js"></script>
  <script type="text/javascript">
    $(function() {

      $('#chart-container').orgchart({
        'data' : $('#org')
      });

    });

	/*
    $(function() {

      $('#chart-container').orgchart({
        'depth': 2,
        'pan': true,
        'data' : datascource,
        'nodeContent': 'title',
        'createNode': function($node, data) {
          $node.on('click', function(event) {
            if (!$(event.target).is('.edge, .toggleBtn')) {
              var $this = $(this);
              var $chart = $this.closest('.orgchart');
              var newX = window.parseInt(($chart.outerWidth(true)/2) - ($this.offset().left - $chart.offset().left) - ($this.outerWidth(true)/2));
              var newY = window.parseInt(($chart.outerHeight(true)/2) - ($this.offset().top - $chart.offset().top) - ($this.outerHeight(true)/2));
              $chart.css('transform', 'matrix(1, 0, 0, 1, ' + newX + ', ' + newY + ')');
            }
          });
        }
      });
    });
	*/


  </script>
  </body>
</html>