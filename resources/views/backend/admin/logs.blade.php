<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Log Viewer</title>
    <!--====== LineAwesome ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/line-awesome.min.css') }}">
    <!--====== select2 CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/select2.min.css') }}">
    <!--====== Nestable CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/nestable.css') }}">
    <!--====== Summernote CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/summernote-lite.min.css') }}">
    <!--====== datatable ======-->
    <link href="{{ static_asset('admin/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!--====== AppCSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/app.css') }}?v=1.0.0">
    <link rel="stylesheet" href="{{ static_asset('admin/css/responsive.min.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .log-entry {
            margin-bottom: 15px;
            padding: 15px;
            border-left: 5px solid #f0f2f5;
            border-radius: 4px;
        }

        .log-entry.error {
            border-left-color: #e74c3c;
            background-color: #fdecea;
        }

        .log-entry.warning {
            border-left-color: #f1c40f;
            background-color: #fcf8e3;
        }

        .log-entry.info {
            border-left-color: #3498db;
            background-color: #eaf4fc;
        }

        .log-entry.debug {
            border-left-color: #2ecc71;
            background-color: #eafaf1;
        }

        pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 14px;
        }

        .log-type {
            font-weight: bold;
            margin-right: 10px;
        }

        .log-timestamp {
            font-style: italic;
            font-size: 12px;
            color: #666;
        }

        .no-log {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

</head>

<body>
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="bg-white redious-border p-20 p-sm-30 mb-4 mb-xl-0">
                        <h1 class="text-center mb-4 mt-4">{{ setting('company_name') }} Log Viewer</h1>
                        @if ($logContent)
                            <div class="list-group">
                                @foreach (explode("\n", $logContent) as $logLine)
                                    @php
                                        $logClass = '';
                                        $logType = 'LOG';
                                        $timestamp = '';
                                        if (strpos($logLine, 'ERROR') !== false) {
                                            $logClass = 'error';
                                            $logType = 'ERROR';
                                        } elseif (strpos($logLine, 'WARNING') !== false) {
                                            $logClass = 'warning';
                                            $logType = 'WARNING';
                                        } elseif (strpos($logLine, 'INFO') !== false) {
                                            $logClass = 'info';
                                            $logType = 'INFO';
                                        } elseif (strpos($logLine, 'DEBUG') !== false) {
                                            $logClass = 'debug';
                                            $logType = 'DEBUG';
                                        }
                                        if (preg_match('/^\[(.*?)\]/', $logLine, $matches)) {
                                            $timestamp = $matches[1];
                                        }
                                    @endphp
                                    <div class="log-entry list-group-item {{ $logClass }}">
                                        <span class="log-type">{{ $logType }}</span>
                                        <span class="log-timestamp">{{ $timestamp }}</span>
                                        <pre>{{ $logLine }}</pre>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-log">
                                <p>No log content available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
