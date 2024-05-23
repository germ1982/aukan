<?php

echo '<style>
        #show,#hide {
            display:none;
        }

        div#content {
            display:none;
            /*padding:10px;
            background-color:#f6f6f6;*/
            width:200px;
            cursor:pointer;
            position: fixed;
            top: 270px;
            left: 0px;
        }

        input#show:checked ~ div#content {
            display:block;
        }

        input#hide:checked ~ div#content {
            display:none;
        }
    </style>';

