<head>
    <title>Assessment</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>

    <link rel="stylesheet" href="./styles.css">

    <style>
        <?php include "styles.css" ?>.card-style {
            margin: 5rem auto 5rem;
            max-width: 400px;
        }

        .link-title {
            margin-bottom: 1.5rem;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('select').material_select();
            $(".dropdown-button").dropdown();
        });

        //function to handle the opening and closing of the create client popup
        function createClient() {
            var popup = document.getElementById("popup");
            popup.classList.toggle("hide");
        }


        function getUser() {

            $('tr').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('grey-text');
                data = $(this).closest('tr').find('td').siblings().map(function() {
                    return $(this).text();
                }).toArray();

                form_data = {
                    'name': data[0],
                    'client_code': data[1],
                }
                var currIndex = 0;
                $(document).ready(function() {
                    $(".input").each(function() {
                        $(this).val(data[currIndex++]);
                    });
                });
                $.ajax({
                    url: "index.php",
                    type: "POST",
                    data: {
                        'name': data[0],
                        'code': data[1]
                    },

                })



            })

        }


        function getContact() {

            $('tr').on('click', function() {

                $(this).toggleClass('grey-text');
                data = $(this).closest('tr').find('td').siblings().map(function() {
                    return $(this).text();
                }).toArray();

                var currIndex = 0;
                $("#contact-form").ready(function() {
                    $(".input2").each(function() {
                        $(this).val(data[currIndex++]);
                    });
                });


            })

        }
    </script>

</head>

<body>

    <div class="col s12 m12 l12">
        <nav class=" red lighten-3" style="padding: 0 2rem;">
            <div class="nav-wrapper">
                <a href="./index.php" class="brand-logo">Contacts & Clients</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="./index.php">Clients</a></li>
                    <li><a href="./contacts.php">Contacts</a></li>

                </ul>
            </div>
        </nav>