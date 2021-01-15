<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <style>
            body { 
                font-family: DejaVu Sans, sans-serif;
            }
            #mail_template #header, #mail_template #footer {
                height: auto;
                border: none;
                padding: 10px 15px;
                text-align: center;
                clear: both;
                overflow-wrap: break-word;
            }
            #mail_template #header {
                font-size: 15px;
                font-weight: bold;
            }
            #mail_template #footer {
                font-size: 12px;
            }
            #mail_template #body {
                height: auto;
                border: none;
                font-size: 13px;
                padding: 15px;
                clear: both;
                overflow-wrap: break-word;
                overflow: hidden;
                line-height: 16px;
            }
            .MsgBody .Object {
				margin: 0;
    			display: inline-block;
			}
			.MsgBody .Object-hover {
				margin:0;
			}
            .odobri, .link {
                min-width: 100px;
                height: auto;
                background-color: white;
                border: 1px solid #cccccc;
                border-radius: 5px;
                box-shadow: 5px 5px 8px #cccccc;
                text-align: center !important;
                padding: 10px;
                background: #007cc3 !important;
                color: white !important;
                font-weight: bold;
                font-size: 12px;
                margin: 10px;
                float: left;
                margin-left: 0;
            }
            
            .marg_20 {
                margin-bottom:20px;
            }
            .marg_top_20 {
                margin-top:20px;
            }
            .company_logo {
                max-height: 20px;
				max-width: 85px;
				margin: 10px 0;
			}
			div p {
				margin: 0;
				padding: 5px;
			}
        </style>
	</head>
	<body>
        @lang('absence.absences')  
        @foreach($day_absences as $day_absence)
            <p>{{ $day_absence->absence['zahtjev'] . ', ' . $day_absence['ime'] . ', ' . (string)$day_absence['period'] . ', ' . $day_absence['vrijeme'] }}</p>
        @endforeach
    </body>
</html>