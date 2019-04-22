$(document).ready(function(){

    $.ajax({
        url : "getjson.php",
        type : "GET",
        success : function(data){
            var datetimes = [];
            var timestamp = [];
            var Otemperature = [];
            var Ohumidity = [];
            var Ttemperature = [];
            var Thumidity = [];
            var lastOtemp = 0;
            var lastOhum = 0;
            var lastTtemp = 0;
            var lastThum = 0;
            var go = false;
            for(var i in data) {
                datetimes.push(data[i]['date']+' '+data[i]['time']);
                timestamp.push(data[i]['timestamp']);
                if(
                    (lastOtemp > 0)&&
                    (lastOhum > 0)&&
                    (lastTtemp > 0)&&
                    (lastThum > 0)
                ) {go = true;}
                if(data[i]['Otemp']) {
                    if(go){Otemperature.push(data[i]['Otemp'])}
                    lastOtemp = data[i]['Otemp'];
                } else {Otemperature.push(lastOtemp);}
                if(data[i]['Ohum']) {
                    if(go){Ohumidity.push(data[i]['Ohum'])}
                    lastOhum = data[i]['Ohum'];
                } else {Ohumidity.push(lastOhum);}
                if(data[i]['Ttemp']) {
                    if(go){Ttemperature.push(data[i]['Ttemp'])}
                    lastTtemp = data[i]['Ttemp'];
                } else {Ttemperature.push(lastTtemp);}
                if(data[i]['Thum']){
                    if(go){Thumidity.push(data[i]['Thum'])}
                    lastThum = data[i]['Thum'];
                } else {Thumidity.push(lastThum);}
            }
            var chartdata = {
                labels: datetimes,
                datasets: [
                    {
                        label: "Office C",
                        fill: false,
                        lineTension: 0.3,
                        backgroundColor: "rgba(218, 124, 48, 1)",
                        borderColor: "rgba(218, 124, 48, 1)",
                        pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
                        borderWidth: 2,
                        pointHoverBorderColor: "rgba(255, 0, 0, 1)",
                        pointRadius: 0,
                        data: Otemperature
                    },
                    {
                        label: "Terrapin C",
                        fill: false,
                        lineTension: 0.3,
                        backgroundColor: "rgba(204, 37, 41, 1)",
                        borderColor: "rgba(204, 37, 41, 1)",
                        pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
                        borderWidth: 2,
                        pointHoverBorderColor: "rgba(255, 0, 0, 1)",
                        pointRadius: 0,
                        data: Ttemperature
                    }
                ]
            };
            var chartdata2 = {
                labels: datetimes,
                datasets: [
                   {
                        label: "Office %",
                        fill: false,
                        lineTension: 0.3,
                        backgroundColor: "rgba(62,150,81,1)",
                        borderColor: "rgba(62,150,81,1)",
                        pointHoverBackgroundColor: "rgba(0,102,255,1)",
                        borderWidth: 2,
                        pointHoverBorderColor: "rgba(0,102,255,1)",
                        pointRadius: 0,
                        data: Ohumidity
                    },{
                        label: "Terrapin %",
                        fill: false,
                        lineTension: 0.3,
                        backgroundColor: "rgba(57,106,177,1)",
                        borderColor: "rgba(57,106,177,1)",
                        pointHoverBackgroundColor: "rgba(0,102,255,1)",
                        borderWidth: 2,
                        pointHoverBorderColor: "rgba(0,102,255,1)",
                        pointRadius: 0,
                        data: Thumidity
                    }
                ]
            };
            var ctx = $("#mycanvas");
            var LineGraph = new Chart(ctx, {
                type: 'line',
                data: chartdata
            });
            var ctx2 = $("#mycanvas2");
            var LineGraph = new Chart(ctx2, {
                type: 'line',
                data: chartdata2
            });
            var dial1scale = 40
            var dial2scale = 40
            $('.gauge-arrow').cmGauge();
            $('.gauge-arrow.arrow1').trigger('updateGauge', Math.ceil(100*lastOtemp/dial1scale));
            $('.officetemp').html(lastOtemp+' C')
            $('.gauge-arrow.arrow2').trigger('updateGauge', Math.ceil(100*lastTtemp/dial2scale));
            $('.terrapintemp').html(lastTtemp+' C')
        },
        error : function(data) {

        }
    });


});