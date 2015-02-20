<?php
//To do insert inside kana

shell_exec('rrdtool xport -s now-24h -e now DEF:a=/etc/kana/data/temp/temp1.rrd:temp:AVERAGE XPORT:a:"Chambre Remi" > /var/www/kana/data/temperature24h.xml');
$actual_temp =  shell_exec('rrdtool lastupdate /etc/kana/data/temp.rrd');
$temp_data = explode(":",$actual_temp);
//print_r($temp_data);
$time = $temp_data[0];
$time = explode("\n",$time);
$time = $time[2];
$time = date('H:i:s d-m-Y ',$time);

$temp = $temp_data[1];

?>
<div id="content" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php
//<button onclick="getdata()">GET DATA</button>
echo "<h1>Dernier relevé: ".$temp."°C à ".$time."<h1>";
include(CORE_VIEWS."footer/footer.view");
?>

<script>
Highcharts.setOptions({
    global: {
        useUTC: false
    }
});

options = {
    chart: {
        renderTo: 'content',
        type: 'spline'
    },
    title: {
        text: 'Température sur 24 heures'
    },
    subtitle: {
        text: ''
    },
    colors: ['#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', 
       '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'],
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: {
            hour: '%H. %M',
        }
    },
    yAxis: {
        title: {
            text: 'T (°C)'
        }
    },
    tooltip: {
        formatter: function() {
            return '<b>' + this.series.name + '</b><br/>' + Highcharts.dateFormat('%H:%M', this.x) + ': ' + this.y.toFixed(1) + '°C';
        }
    },

    plotOptions: {
        series: {
            marker: {
                radius: 2
            }
        }
    },

    lineWidth: 1,

    series: []
}

function computeSunrise(day, sunrise) {

        /*Sunrise/Sunset Algorithm taken from
        http://williams.best.vwh.net/sunrise_sunset_algorithm.htm
        inputs:
            day = day of the year
            sunrise = true for sunrise, false for sunset
        output:
            time of sunrise/sunset in hours */

       //lat, lon for Berlin, Germany
       var longitude = 13.408056;
       var latitude = 52.518611;
       var zenith = 90.83333333333333;
       var D2R = Math.PI/180;
       var R2D = 180/Math.PI;

       // convert the longitude to hour value and calculate an approximate time
       var lnHour = longitude/15;
       var t;
       if (sunrise) {
           t = day + ((6-lnHour)/24);
       } else  {
           t =day + ((18-lnHour)/24);
       };

       //calculate the Sun's mean anomaly
       M = (0.9856 * t) - 3.289;

       //calculate the Sun's true longitude
       L = M + (1.916 * Math.sin(M*D2R)) + (0.020 * Math.sin(2 * M* D2R)) + 282.634;
       if (L > 360) {
           L = L - 360;
       } else if (L < 0) {
           L = L + 360;
       };

       //calculate the Sun's right ascension
       RA = R2D*Math.atan(0.91764 * Math.tan(L*D2R));
       if (RA > 360) {
           RA = RA - 360;
       } else if (RA < 0) {
           RA = RA + 360;
       };

       //right ascension value needs to be in the same qua
       Lquadrant  = (Math.floor(L/(90))) * 90;
       RAquadrant = (Math.floor(RA/90)) * 90;
       RA = RA + (Lquadrant - RAquadrant);

       //right ascension value needs to be converted into hours
       RA = RA / 15;

       //calculate the Sun's declination
       sinDec = 0.39782 * Math.sin(L*D2R);
       cosDec = Math.cos(Math.asin(sinDec));

       //calculate the Sun's local hour angle
       cosH = (Math.cos(zenith*D2R) - (sinDec * Math.sin(latitude*D2R))) / (cosDec * Math.cos(latitude*D2R));
       var H;
       if (sunrise) {
           H = 360 - R2D*Math.acos(cosH)
       } else {
           H = R2D*Math.acos(cosH)
       };
       H = H /15;

       //calculate local mean time of rising/setting
       T = H + RA - (0.06571 * t) - 6.622;

       //adjust back to UTC
       UT = T - lnHour;
       if (UT > 24) {
           UT = UT - 24;
       } else if (UT < 0) {
           UT = UT + 24;
       }

       //convert UT value to local time zone of latitude/longitude
       localT =  UT  + 1;

       //convert to Milliseconds
       return localT*3600*1000;
   }

   function dayOfYear() {
       var yearFirstDay = Math.floor(new Date().setFullYear(new Date().getFullYear(), 0, 1) / 86400000);
       var today = Math.ceil((new Date().getTime()) / 86400000);
       var dayOfYear = today - yearFirstDay;
       return dayOfYear;
   }

function GetUrlPath() {
    urlPath = window.location.pathname.split(".")[0].substring(1).split("/")[1];
    if (urlPath == "humidity") {
        return "humid"
    } else {
        return "temperature"
    }
}
urlPath = GetUrlPath();

// return everything after the question mark
function GetUrlParameter() {
    idx = window.location.href.indexOf("?");
    if (idx < 0) return "";
    return window.location.href.substring(idx + 1);
}
urlParameter = GetUrlParameter();

function GetChartXml() {
    switch (urlParameter) {
    case "3h":
    case "48h":
    case "1w":
    case "1m":
    case "3m":
    case "1y":
    case "1yminmax":
        return "data/" + urlPath + urlParameter + ".xml";
    }
    return "data/" + urlPath + "24h.xml";
}

function GetChartTitle() {

    var type = "Température";
    if (urlPath == "humid") {
        type = "Humidity"
    };
    switch (urlParameter) {
    case "3h":
        return type + " of the last 3 hours";
    case "48h":
        return type + " of the last 48 hours";
    case "1w":
        return type + " of the last week";
    case "1m":
        return type + " of the last month";
    case "3m":
        return type + " of the last 3 month";
    case "1y":
        return type + " of the last year";
    case "1yminmax":
        return "Min-Max " + type + " of the last year";
    }
    return type + " des dernières 24 heures";
}

</script>

<script type="text/javascript">
    $(document).ready(function() {

      $.ajax({
        type: "GET",
        url: GetChartXml(),
        dataType: "xml",
        success: function(xml) {
          var series = []

          //define series
          $(xml).find("entry").each(function() {
            var seriesOptions = {
              name: $(this).text(),
              data: []
            };
            options.series.push(seriesOptions);
          });

          //populate with data
          $(xml).find("row").each(function() {
            var t = parseInt($(this).find("t").text()) * 1000

            $(this).find("v").each(function(index) {
              var v = parseFloat($(this).text())
              v = v || null
              if (v != null) {
                options.series[index].data.push([t, v])
              };
            });
          });

          options.title.text = GetChartTitle()
          $.each(series, function(index) {
            options.series.push(series[index]);
          });

          //add sunrise and sunset
          no_sun = ['1yminmax', '1y', '3m'];     
          if ($.inArray(urlParameter, no_sun) == -1) 
          {
            options.xAxis.plotBands = []
            for (var i = 31; i >= 0; i--) {
              var d = new Date();
              d.setHours(0,0,0,0);
              d.setDate(d.getDate()-i);
              var sunrise = d.getTime()+computeSunrise(dayOfYear(), true);
              var sunset = d.getTime()+computeSunrise(dayOfYear(), false);
              options.xAxis.plotBands.push({
                from: sunrise,
                to: sunset,
                color: '#FCFFC5'
              });
            };
          };
          chart = new Highcharts.Chart(options);
        }
      });
    });
  </script>