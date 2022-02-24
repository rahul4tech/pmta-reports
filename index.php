<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

    <title>PMTA REPORTS</title>
</head>

<body>
    <form method="POST">
        <div class="container">
            <div>
                <h1>PMTA Reports</h1>
            </div>
            <hr>
            <div class=" offset-md-3">
                <label>Select Date: </label>
                <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                    <input name="date" class="form-control" type="text" readonly />
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
                <h4>Please Enter Domains :</h4>

                <div class="form-group">
                    <div class="col-md-5">
                        <textarea name="domains" class="form-control" rows="10" placeholder="Enter Domains, Domain / Line" required></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <hr>
            </div>
            <div class="col-md-4  offset-md-1">

                <button type="submit" class="btn btn-lg btn-primary">Get Reports</button> || <button type="reset" class="btn btn-lg btn-danger">Reset</button>
            </div>
        </div>

    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                autoclose: true,
                todayHighlight: true
            }).datepicker('update', new Date());
        });
    </script>
</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $date = $_REQUEST['date'];
    $filename = 'reports_' . date("YmdHis") . '.csv';

    $myfile = fopen($filename, "w") or die("Unable to open file!");

    function getReports($domain, $date)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://$domain:1212/getFile?file=%2Fvar%2Flog%2Fpmta%2Facct-$date-0000.csv",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    $domains = array_filter(explode("\r\n", $_POST['domains']));

    foreach ($domains as $domain) {
        // echo $domain . "<br>";

        // array_push($dataRecords, getReports($domain, $dataRecords));
        fwrite($myfile, getReports($domain, $date));
    }

    exit(header("location:download.php?filename=" . base64_encode($filename)));
}

?>