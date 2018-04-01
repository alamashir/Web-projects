<Html>
    <Head>
        <style>
            #E1{
               border: 1px solid lightgrey;
                width: 65%;
                 border-spacing:0; 
                 border-collapse: collapse;
                
            }
            #t10
            {   
                border: 1px solid lightgrey;
                width: 75%;
                border-spacing:0; 
                border-collapse: collapse;
                background-color: #F5F4F5;
            }
            #t10 td{
                border-width: thin;
                border-bottom: 1px solid lightgrey;
            }
            
            a:link{
                text-decoration: none;
            }
            a:visited {
                color: blue;
            }
            #E1 td{
                border-width: thin;
                border-right:1px solid lightgrey;
                font-weight: bold;
                column-gap: normal;
                border-bottom: 1px solid lightgrey;
                background-color: #F5F4F5;
                
            }
            #E1 td+td{
                text-align: center;
                font-weight: normal;
                border-right: none;
                background-color: #FCFBFD;
            }
            #tab1{
                border: 1px solid lightgrey;
                background-color: whitesmoke;
                padding-right: 20px;
                padding-bottom: 27px;
            }
            
        
            #maintable{
                border: 1px solid lightgrey;
                width: 75%;
                 border-spacing:0; /* Removes the cell spacing via CSS */
                    border-collapse: collapse;
                
            }
            #maintable td{
                font-weight: bold;
                border-bottom: 1px solid lightgrey;
                border-width: thin;
                border-right:1px solid lightgrey;
                background-color: #F5f4F6;
                
            }
            #maintable td+td{
                text-align: center;
                font-weight: normal;
                border-right: none;
                background-color: #FCFBFD;
            }
            #indicators{
                
                float left: 80%;
            }
            #container{
                width:1065px;
                height:600px;
                border: 1px solid;
                border-color: lightgrey;
            }
        
        </style>
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </Head>
    <body>
    
    <form action="" name="myform" method="POST" id="location">
    
        <center><table id="tab1"><tr><td id="row1"><center><i><font size="6px" >Stock Search</font></i></center></td></tr>
        <tr><td><hr color=lightgrey width=105%>Enter Stock Ticker Symbol:*
        <input type="text" id="stock" name="stocksym" maxlength="260" size="20"  value="<?php echo isset($_POST['stocksym'])?$_POST['stocksym']:"" ?>">
            </td></tr>
        <tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" onclick="check()" value="Search" >
             <input type="button" id="reset" onclick="r()" value="Clear" ></center></td></tr>
            <tr><td>*- <i>Mandatory fields.</i></td></tr>
        </table> </center> </form>
        
        
        <script type="text/javascript">
            
            function r()
            {
               
                document.getElementById("stock").value="";
                document.getElementById("f").style.display= " none ";
                document.getElementById("x").style.display= " none ";
            }
            
            
            
            
        </script>
        
       
        
         <?php    if(isset($_POST["submit"]) && (!empty($_POST["stocksym"]))):  ?>
         <?php
       $response=file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&outputsize=`&symbol=".$_POST['stocksym']."&apikey=19CXZPEFGHMDNAHA");
        
        $t=false;
        if(preg_match("/Error/",$response))
        {
            echo "<div id='x'><center><Table id='E1'><tr><td>Error&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td> Error:NO Record has been found,please enter a valid symbol</td></tr></table></div></center>";
            $t=true;
        } 
        
        
    ?>
        <?php endif; ?>
        
        <div id="f" >
        
        <script type="text/javascript">
            function check()
            {
            if(document.getElementById("stock").value=="" ||document.getElementById("stock").value==null)
            {
                alert("Please enter a Symbol");
            }
            
            }
      
        </script>
        
    
    <?php    if(isset($_POST["submit"]) && (!empty($_POST["stocksym"])) && !($t)):  ?>
   
    
        
      <?php   
    $response=file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&outputsize=full&symbol=".$_POST['stocksym']."&apikey=19CXZPEFGHMDNAHA");
        
    $my_array = json_decode($response,true);
    $Time_Daily=$my_array["Time Series (Daily)"];
        
    $days= array_keys($Time_Daily);
    $metadataref=$my_array["Meta Data"]["3. Last Refreshed"];
    $close=$my_array["Time Series (Daily)"]["$metadataref"]["4. close"];
    $pclose=$my_array["Time Series (Daily)"]["$days[2]"]["4. close"];
    $change=round($close-$pclose,2);
    $changepert=round($change*100/$pclose,2);
    $days1=array_slice($days,0,130);
    $revdays=array_reverse($days1);
    $closemin=$my_array["Time Series (Daily)"][$revdays[0]]["4. close"];
    $maxclose=0;
    $volm=0;
      
       
    echo "<center><Table id='maintable' width='25%'>"."<tr>";
    echo "<td><b>Stock Ticker Symbol</b></td><td>".$my_array["Meta Data"]["2. Symbol"]."</td></tr>";
    echo "<tr>";
       
    echo "<td>Close</td><td>".$my_array["Time Series (Daily)"]["$metadataref"]["4. close"]."</tr>";
    echo "<tr>";
    echo "<td>Open</td><td>".$my_array["Time Series (Daily)"]["$metadataref"]["1. open"]."</tr>";
    echo "<tr>";
    echo "<td>Previous close</td><td>".$my_array["Time Series (Daily)"]["$days[2]"]["4. close"]."</tr>"; 
    
    if($change>=0)
    {
    echo "<tr>";
    echo "<td>Change</td><td>".$change."<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='12px' height='12px'></img></td></tr>";
    echo "<tr>";
    echo "<td>Change Percent</td><td>".$changepert."%<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='12px' height='12px'></img></td></tr>";
    }
    else
    {
    echo "<tr>";
    echo "<td>Change</td><td>".$change."<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='12px' height='12px'></img></td></tr>";
    echo "<tr>";
    echo "<td>Change Percent</td><td>".$changepert."%<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='12px' height='12px'></img></td></tr>";
    
    
    }
    echo "<tr>";
    echo "<td>Day's Range</td><td>".$my_array["Time Series (Daily)"]["$metadataref"]["3. low"]."-".$my_array["Time Series (Daily)"]["$metadataref"]["2. high"].
        "</tr>";
    echo "<tr>";
    echo "<td>Volume</td><td>".$my_array["Time Series (Daily)"]["$metadataref"]["5. volume"]."</tr>";
    echo "<tr>";
    echo "<td>Timestamp</td><td>".$my_array["Meta Data"]["3. Last Refreshed"]."</tr>";
    echo "<tr class='last'>";
    echo "<td>Indicators</td><td> <div id='indicators'><a href =# onclick='pricee()'>Price
      </a>
      <a href =# onclick='sma()'>&nbsp&nbspSMA
      </a>
      <a href =# onclick='ema()'>&nbsp&nbspEMA
      </a>
      <a href =# onclick='stoch()'>&nbsp&nbspSTOCH
      </a>
      <a href =# onclick='rsi()'>&nbsp&nbspRSI
      </a>
      <a href =# onclick='adx()'>&nbsp&nbspADX
      </a>
      <a href =# onclick='cci()'>&nbsp&nbspCCI
      </a>
      <a href =# onclick='bbands()'>&nbsp&nbspBBANDS
      </a>
      <a href =# onclick='macd()'>&nbsp&nbspMACD
      </a>
      </div></td></tr>"; 
    
    
        
    
    echo "</center></table><br>";
     
        ?> 
    
        
    
    <?php
       for($j=0;$j<sizeof($revdays);$j++)
            {
                if(($my_array["Time Series (Daily)"][$revdays[$j]]["4. close"])>$maxclose)
                {
                    $maxclose=$my_array["Time Series (Daily)"][$revdays[$j]]["4. close"];
                }
                if(($my_array["Time Series (Daily)"][$revdays[$j]]["4. close"])<$closemin)
                {
                    $closemin=$my_array["Time Series (Daily)"][$revdays[$j]]["4. close"];
                }
                if(($my_array["Time Series (Daily)"][$revdays[$j]]["5. volume"])>$volm)
                {
                    $volm=$my_array["Time Series (Daily)"][$revdays[$j]]["5. volume"];
                }
            } 
            
       
        
    ?>    
        
    <?php echo '<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"> </div>'?>
    <?php 
    $weeks=file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_WEEKLY&symbol=".$_POST['stocksym']."&apikey=19CXZPEFGHMDNAHA");
    $week=json_decode($weeks,true);
    $weekly=$week["Weekly Time Series"];
        
    $weekdays= array_keys($weekly);   
    $revweeklydays=array_reverse($weekdays);
      
    for($i=0;$i<60;$i++)
    {
        $tempdays[]=$weekdays[$i];
    }
    $revtemp=array_reverse($tempdays);  
    
    $date= substr("$metadataref",5,2).'/'.substr("$metadataref",8,2).'/'.substr("$metadataref",0,4);
                
     
        ?>    
        
    
   <script>    
      
    Highcharts.chart('container', {
    chart: {
        zoomType: 'xy',
        
    },
    title: {
        text: 'Stock Price (<?php echo $date ?>)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
        
    },
    xAxis: [{
        categories: [<?php for($i=0;$i<sizeof($revdays);$i++)
                        {echo "'" ;
                        
                        echo substr("$revdays[$i]",5,2);
                        echo "/";   
                        echo substr("$revdays[$i]",8,2);
                        
                        echo "'";
                        echo ",";
                        }
                    ?>
                ],
        
        labels: {
            rotation: -45
        },
        crosshair: true,
        tickInterval: 5
    }],
    yAxis: [{
       min: [<?php echo $closemin-5; ?>],
        max: [<?php echo $maxclose; ?>],
        title: {
            text: 'Stock Price',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
             labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
            
            tickInterval: 5,
        tooltip: {
        shared: true,
        formatter: function()
        {
            var s ='<b>' +this.x+ '</b><b>'+ this.y+'</b>';
            return s;
        }
        },
    
        
    }, { // Secondary yAxis
        max: [<?php echo $volm*4; ?>],
        title: {
            text: 'Volume',
            formatter: function(){
                return this.value/1000000 +"M"
            },
            style: {
                color: Highcharts.getOptions().colors[1]
            }
            
        },
        labels: {
            formatter: function(){
                return this.value/1000000 +"M"
            },
            
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        opposite: true
    }],
        legend:{
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            
        },
    tooltip: {
        shared: false,
        useHTML: true,
        
    },
   
    
    series: [ 
    
         {    
        
        name: '<?php echo $_POST['stocksym'] ?>',
        type: 'area',
        color: '#FF0000',
        fillOpacity: 0.6,
        
        data: [<?php
            for($j=0;$j<sizeof($revdays);$j++)
            {
                echo $my_array["Time Series (Daily)"][$revdays[$j]]["4. close"];
                echo ",";
                
            }
            
            ?>],
            marker: {
                enabled: false
            },
         },
    
    {
        name: '<?php echo $_POST['stocksym'] ?> Volume',
        color: 'white',
        type: 'column',
        yAxis: 1,
        data: [<?php
            for($j=0;$j<sizeof($revdays);$j++)
            {
                echo $my_array["Time Series (Daily)"][$revdays[$j]]["5. volume"];
                echo ",";
                
            }
            
            ?>],
       
    },
    
    
    ]
        });
        
        
        
    
       </script>
    
        
          
        
        
        
           <script type="text/javascript">
               
            
            function sma(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=SMA&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
                console.log(data);
                
            var data1=[];
                data1=data["Technical Analysis: SMA"];
                var d2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: SMA"][item[i]]["SMA"]));
                        
                    }
              
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["SMA"])); 
                        
                    }
               
                d2=maindatasma.slice(0,130);
                d2.reverse();
                
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
                Highcharts.chart('container', {
                    chart: {
        
        
    },

    title: {
        text: 'Simple Moving Average (SMA)'
    },

    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis:{
        categories: cat
                ,
        
       
        labels: {
            rotation: -45
        },
       tickInterval: 5,
        
    
    },
    yAxis: {
        title: {
            text: 'SMA'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
                    plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
                   

    series: [{
        name: '<?php echo $_POST['stocksym'] ?>',
        
        data: d2,
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }],

    

});
                 
            }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
                
            }
        </script> 
       
        
        
    
        
        
      
         <script type="text/javascript">
               
            
            function stoch(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=STOCH&symbol="+x+"&interval=daily&slowkmatype=1&slowdmatype=1&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: STOCH"];
                var d2=[];
                var D3=[];
                var slowd=[];
                var slowk=[];
                
                 console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                for(var i in data1)
                    {
                       
                         slowd.push(parseFloat( data1[i]["SlowD"])); 
                        slowk.push(parseFloat( data1[i]["SlowK"])); 
                    }
                
                d2=slowd.slice(0,130);
                
               d2.reverse();
                D3=slowk.slice(0,130);
                D3.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
                Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Stochastic Oscillator (STOCH)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'STOCH'
        },
        
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    plotOptions: {
        series: {
            lineWidth: 1,
            
        }
    },
    series: [{
        name: '<?php echo $_POST['stocksym'] ?> SlowD',
        marker: {
            symbol: 'circle'
        },
        data:d2,
        
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },

    }, {
        name: '<?php echo $_POST['stocksym'] ?> SlowK',
        marker: {
            symbol: 'circle'
        },
        data:D3,
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }]
});      
        
         }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
            
                   
                
            }
        </script> 
        
        
           <script type="text/javascript">
               
            
            function ema(){
            var x=document.getElementById("stock").value;
           
            var response="https://www.alphavantage.co/query?function=EMA&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: EMA"];
                var d2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: EMA"][item[i]]["EMA"]));
                        
                    }
               
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["EMA"])); 
                        
                    }
                
                d2=maindatasma.slice(0,130);
                d2.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Exponential Moving Average(EMA)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'EMA'
        },
        labels: {
            formatter: function () {
                return this.value ;
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    series: [ {
        name: '<?php echo $_POST['stocksym'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: d2,
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
            
                   
                
            }
        </script> 
        
        
   
        
        
        <script type="text/javascript">
               
            
            function rsi(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=RSI&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: RSI"];
                var d2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: RSI"][item[i]]["RSI"]));
                        
                    }
               
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["RSI"])); 
                        
                    }
                
                d2=maindatasma.slice(0,130);
                d2.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Relative Strength Index (RSI)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'RSI'
        },
        
    },
            legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
            plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    
    series: [ {
        name: '<?php echo $_POST['stocksym'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: d2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
            
                   
                
            }
        </script> 
        
        
        <!--CCI --> 
        
        
        
        <script type="text/javascript">
               
            
            function cci(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=CCI&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: CCI"];
                var d2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: CCI"][item[i]]["CCI"]));
                        
                    }
               
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["CCI"])); 
                        
                    }
                
                d2=maindatasma.slice(0,130);
                d2.reverse();
                
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Commodity Channel Index (CCI)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'CCI'
        },
        
    },
            legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
            plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    series: [ {
        name: '<?php echo $_POST['stocksym'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: d2,
        
     marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
            
                   
                
            }
        </script> 
        
        <!--ADX --> 
        
        
        <script type="text/javascript">
               
            
            function adx(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=ADX&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: ADX"];
                var d2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: ADX"][item[i]]["ADX"]));
                        
                    }
               
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["ADX"])); 
                        
                    }
                
                d2=maindatasma.slice(0,130);
                d2.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Average Directional movement indeX (ADX)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'ADX'
        },
       
    },
            legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
            plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    series: [ {
        name: '<?php echo $_POST['stocksym'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: d2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
            
                
            }
        </script> 
        
        
        <!-- //BBands --> 
        
        <script type="text/javascript">
               
            
            function bbands(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=BBANDS&symbol="+x+"&interval=daily&time_period=5&series_type=close&nbdevup=3&nbdevdn=3&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: BBANDS"];
                var d2=[];
                var D3=[];
                var data4=[];
                var lb=[];
                var ub=[];
                var mb=[];
                
                 console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
        
        
                for(var i in data1)
                    {
                       
                         lb.push(parseFloat( data1[i]["Real Lower Band"])); 
                        ub.push(parseFloat( data1[i]["Real Upper Band"]));
                        mb.push(parseFloat( data1[i]["Real Middle Band"])); 
                    }
                
                d2=lb.slice(0,130);
                
               d2.reverse();
                D3=mb.slice(0,130);
                D3.reverse();
                data4=ub.slice(0,130);
               data4.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
                
                
                Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Bollinger Bands (BBANDS)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat, 
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'BBANDS'
        },
        
    },
                    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: 'red',
                lineWidth: 1
            }
        }
    },
                     plotOptions: {
        series: {
            lineWidth: 1,
            
        }
    },
    
    series: [{
        name: '<?php echo $_POST['stocksym'] ?> Real Lower Band',
        marker: {
            symbol: 'square'
        },
        data:d2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },

    },{
        name: '<?php echo $_POST['stocksym'] ?> Real Middle Band',
        marker: {
            symbol: 'diamond'
        },
        data:D3,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    },
        {
        name: '<?php echo $_POST['stocksym'] ?> Real Upper Band',
        marker: {
            symbol: 'diamond'
        },
        data:data4,
            marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }]
});      
        
         }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
                
            }
        </script> 
        
        
        <!--MACD --> 
        
        <script type="text/javascript">
               
            
            function macd(){
            var x=document.getElementById("stock").value;
            console.log(x);
            var response="https://www.alphavantage.co/query?function=MACD&symbol="+x+"&interval=daily&series_type=close&fastperiod=10&apikey=19CXZPEFGHMDNAHA";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: MACD"];
                var d2=[];
                var D3=[];
                var data4=[];
                var his=[];
                var sig=[];
                var norm=[];
                
                 console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
        
        
                for(var i in data1)
                    {
                       
                         his.push(parseFloat( data1[i]["MACD_Hist"])); 
                        sig.push(parseFloat( data1[i]["MACD_Signal"]));
                        norm.push(parseFloat( data1[i]["MACD"])); 
                    }
                
                d2=his.slice(0,130);
                
               d2.reverse();
                D3=sig.slice(0,130);
                D3.reverse();
                data4=norm.slice(0,130);
               data4.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
                
                
                Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Moving Average Convergence/Divergence(MACD)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'MACD'
        },
        
    },
        legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: 'red',
                lineWidth: 1
            }
        }
    },
    plotOptions: {
        series: {
            lineWidth: 1,
            
        }
    },
    series: [{
        name: '<?php echo $_POST['stocksym'] ?> MACD_Hist',
        marker: {
            symbol: 'square'
        },
        data:d2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },

    },{
        name: '<?php echo $_POST['stocksym'] ?> MACD_Signal',
        marker: {
            symbol: 'diamond'
        },
        data:D3,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    },
        {
        name: '<?php echo $_POST['stocksym'] ?> MACD',
        marker: {
            symbol: 'diamond'
        },
        data:data4,
            marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }]
});      
        
         }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",response,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        <!--Price -->
        
        
   <script>    

       function pricee()
       {
    Highcharts.chart('container', {
    chart: {
        zoomType: 'xy',
        
    },
    title: {
        text: 'Stock Price (<?php echo $date ?>)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
        
    },
    xAxis: [{
        categories: [<?php for($i=0;$i<sizeof($revdays);$i++)
                        {echo "'" ;
                        
                        echo substr("$revdays[$i]",5,2);
                        echo "/";   
                        echo substr("$revdays[$i]",8,2);
                        
                        echo "'";
                        echo ",";
                        }
                    ?>
                ],
        
        labels: {
            rotation: -45
        },
        crosshair: true,
        tickInterval: 5
    }],
    yAxis: [{
       min: [<?php echo $closemin-5; ?>],
        max: [<?php echo $maxclose; ?>],
        title: {
            text: 'Stock Price',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
             labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
            
            tickInterval: 5,
        tooltip: {
        shared: true,
        formatter: function()
        {
            var s ='<b>' +this.x+ '</b><b>'+ this.y+'</b>';
            return s;
        }
        },
    
        
    }, { // Secondary yAxis
        max: [<?php echo $volm*4; ?>],
        title: {
            text: 'Volume',
            formatter: function(){
                return this.value/1000000 +"M"
            },
            style: {
                color: Highcharts.getOptions().colors[1]
            }
            
        },
        labels: {
            formatter: function(){
                return this.value/1000000 +"M"
            },
            
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        opposite: true
    }],
        legend:{
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            
        },
    tooltip: {
        shared: false,
        useHTML: true,
        
    },
   
    
    series: [ 
    
         {    
        
        name: '<?php echo $_POST['stocksym'] ?>',
        type: 'area',
        color: '#FF0000',
        fillOpacity: 0.6,
        
        data: [<?php
            for($j=0;$j<sizeof($revdays);$j++)
            {
                echo $my_array["Time Series (Daily)"][$revdays[$j]]["4. close"];
                echo ",";
                
            }
            
            ?>],
            marker: {
                enabled: false
            },
         },
    
    {
        name: '<?php echo $_POST['stocksym'] ?> Volume',
        color: 'white',
        type: 'column',
        yAxis: 1,
        data: [<?php
            for($j=0;$j<sizeof($revdays);$j++)
            {
                echo $my_array["Time Series (Daily)"][$revdays[$j]]["5. volume"];
                echo ",";
                
            }
            
            ?>],
       
    },
    
    
    ]
        });
        
        
        
       }
       </script>
        
            
            
        <script>
        
        function changeimg()
            {   
                if(document.getElementById("downarr").src=="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png")
                    {
                document.getElementById("downarr").src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png"
                document.getElementById("clicktext").innerHTML="Click here to hide"
                document.getElementById("t10").style.display="block";
                    }
            
                else 
                    {
                    console.log("hello");    
                document.getElementById("downarr").src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png"
                document.getElementById("clicktext").innerHTML="click to show stock news"
                        document.getElementById("t10").style.display="none";
                    }
            
            
            
            }
            
       
        </script>
        
      
        <?php  
        
        
        $xml=simplexml_load_file('https://seekingalpha.com/api/sa/combined/'.$_POST['stocksym'].'.xml');
        $js=json_encode($xml);
        $jsd=json_decode($js,true);
        
        $newsHeadline = $xml->channel->item[0]->title->__toString();
        $newsLink = $xml->channel->item[0]->link->__toString();
        $newspub=$xml->channel->item[0]->pubDate->__toString();
        $newsInfo[0] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
        
        
        $newsHeadline = $xml->channel->item[1]->title->__toString();
        $newsLink = $xml->channel->item[1]->link->__toString();
        $newspub=$xml->channel->item[1]->pubDate->__toString();
        $newsInfo[1] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
        
        $newsHeadline = $xml->channel->item[2]->title->__toString();
        $newsLink = $xml->channel->item[2]->link->__toString();
        $newspub=$xml->channel->item[2]->pubDate->__toString();
        $newsInfo[2] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
       
        $newsHeadline = $xml->channel->item[3]->title->__toString();
        $newsLink = $xml->channel->item[3]->link->__toString();
        $newspub=$xml->channel->item[3]->pubDate->__toString();
        $newsInfo [3]= array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
        
        $newsHeadline = $xml->channel->item[4]->title->__toString();
        $newsLink = $xml->channel->item[4]->link->__toString();
        $newspub=$xml->channel->item[4]->pubDate->__toString();
        $newsInfo[4] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
         
        $jsonNews = json_encode($newsInfo);
        
        $tempnews=json_decode($jsonNews,true);
        $keys=array_keys($tempnews);
        $count=0;
       
       $article='/article/';
        
       
        echo '<center><font color="grey"><p id="clicktext">click to show stock news</center></font></p>';
        echo '<center><img src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" width="30px" height="15px" id="downarr" onclick="changeimg() "/></center><br><br>';
        echo '<div id="t10" style="display: none">';
        echo '<Table  width="100%">';
        
        for($i=0;$i<sizeof($jsd['channel']['item']);$i++)
        {    
            if(preg_match($article,$jsd['channel']['item'][$i]['link']))
            {
        echo '<tr><td><a href="'.$jsd['channel']['item'][$i]['link'].'"target=" ">'.$jsd['channel']['item'][$i]['title'].'</a>';
        $r=  $jsd['channel']['item'][$i]['pubDate'];  
        $sub= substr($r,0,26);
        echo '&nbsp&nbsp Publicated Time: '.$sub.'</td></tr>';
                $count++;
            }
            if($count==5)
            {
                break;
            }
            
        }
       
        
        echo '</table></div>';
        
        ?>
        
        <?php endif; ?>
        
        
        </div>
    </body>
</html>