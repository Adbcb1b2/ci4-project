<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs Board</title>

    <!-- Link to Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link to Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        
        .custom-background {
            background: linear-gradient(to right, rgb(1, 10, 147), rgb(41, 169, 233)); 
            color: white;
            padding: 15px 0;
            box-shadow: 0px 4px 5px rgba(0, 0, 0, 0.2); 
        }

        .banner-title {
            font-size: 2rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); 
            color: white;
        }

        .banner-title:hover{
            color: darkgrey;
        }

        .align-center {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        
        .search-bar {
            border-radius: 50px;
            border: none;
            padding: 10px 15px;
            font-size: 1rem;
        }

        
        .input-group-text {
            background: white;
            border: none;
            border-radius: 50px 0 0 50px; 
        }

        .card-body {
            position: relative;
        }

        .view-job-btn {
            position: absolute; /* Position the button at the bottom right of the card */
            bottom: 10px;
            right: 10px;
            background: rgb(1, 10, 147);
            color: white;
            border: none;

        }

        .view-job-btn:hover {
            
            background: rgb(41, 169, 233);
            color: white;
            border: none;
        }

        .filter-btn{
            background: rgb(1, 10, 147);
            color: white;
        }

        .filter-btn:hover{
            background: rgb(41, 169, 233);
            color: white;
        }

        .filter-bar {
            background: linear-gradient(to right, rgb(41, 169, 233), rgb(1, 10, 147) ); 
            color: white;
            padding: 15px;
            border-radius: 10px;
        }

        .custom-button{
            background: rgb(1, 10, 147);
            color: white;
            border: none;
            margin: 20px 0px;
        }

        .custom-button:hover{
            background: rgb(41, 169, 233);
            color: white;
            border: none;
        }


    </style>
</head>
<body>