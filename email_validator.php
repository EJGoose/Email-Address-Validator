<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Validator</title>

    <style> 
        /* background styling*/
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f9f9f9;
            font-family: Verdana, Arial, Helvetica, sans-serif; 
        }

        section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color:rgb(255, 255, 255);
            width: 60%;
            padding: 20px;
        }

        /*form styles*/
        form {
            max-width: 450px;
            display: flex;
            flex-direction: column;
        }

        select {
            text-align: center;
            padding: 10px;
        }

        form label {
            margin: 10px;
        }

        form #submit-btn{
            margin-top: 15px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #fff;
            background-color: rgb(65, 110, 225);
            color: white;
        }

        #submit-btn:hover{
            background-color: rgb(51, 84, 168);
        }

        /* tool tip */
        .tooltip {
            position: relative;
            display: inline-block;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 300px;
            text-align: center;
            border-radius: 5px;
            position: absolute;
            top: -5px;
            right: 105%;
            z-index: 1;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            background-color: rgb(167, 191, 252);
            padding: 10px;
            color: black;
            box-shadow: 
                0.6px 0.6px 1.1px hsl(0deg 0% 63% / 0.22),
                3.5px 3.3px 6.3px -0.6px hsl(0deg 0% 63% / 0.38),
                11.6px 10.8px 20.9px -1.2px hsl(0deg 0% 63% / 0.54);
        }

        /* table values */
        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }

        table {
            border: 1px solid #ddd;
            border-collapse: collapse;
            max-width: 700px;
        }

        tr:hover {
            background-color: rgb(97, 179, 198);
        }

        th {
            font-szie: 120%;
            background-color: rgb(152, 236, 255);
        }

        td, th {
            padding: 10px;
            text-align: center;
            vertical-align: center;
            border-bottom: 1px solid #ddd;
        }

        .alert {
            color: rgb(111, 2, 144);
        }
        .no_issue {
            color: rgb(99, 99, 99);
        }

        ul {
            list-style-type: none;
        }

        ol {
            width: 70%;
        }

        li {
            margin: 5px;
        }

        .results {
            margin-bottom: 30px;
        }

    </style>
    
</head>
<body>
    <section class= "introduction">
        <h1>Email Address Validator</h1>
        <p>A valid email address consists of an email username and an email domain.</p>
        <p>eg. john.doe@company.com</p>
        <ol>
            <li>The username must contain only alphanumeric characters, dots, underscores hyphens and the plus symbol. (john.doe)</li>
            <li>The username must be followed by a single @ symbol. (@)</li>
            <li>The domain name may contain alphanumeric characters, hyphens and dots (company)</li>
            <li>This is followed by a top level domain, which must contain at least two characters imediately following a full stop. This must be the end of the email addresss. (.com)</li>
        </ol>
    </section>        
    <!-- html form to allow users to add multiple emails -->
    <section class="validator">
        <h2>Email Checker</h2>
        <form action = "" method = "post"> 
            <label for="emails">Insert Comma Seperated Email addresses to validate:</label>
            <textarea name="emails" id="emails" placeholder="youremail@yourdomain.com, nextemail@..." rows='10' cols='100'></textarea>

            <!-- A tool tip explaining how strict mode works-->
            <div class="tooltip">
                <label for="mode">Choose appropriate mode:
                
                    <span class="tooltip-text">Strict mode removes additional special characters and disallows double full stops</span>
                </label>
            </div>
            

            <select name="mode" id="mode"> 
                <option value="">Normal</option>
                <option value="strict">Strict Mode</option>
            </select>

            <input id="submit-btn" type="submit" value="Submit"/>
        </form>
    </section>
    
    <section class="results">
        <?php
            #regex explanation
            // sample email pattern
            // ^ must match the start of the string
            // username containing alphanumeric characters, dots, underscores and hyphens
            // the plus represents one or more of these characters
            // the @ symbol
            // domain name: alphanumeric characters, hyphens and dots followed by a plus again to signify one or more of these characters
            // the full stop character \ escaped with a forwardslash
            // top level domain should only contain alphabetical characters and must be 2 or more characters in length
            // the dollar sign signifies that this must be the end of the string

            //check if strict mode needs to be applied        
            if (isset($_POST["mode"]) && $_POST["mode"] == "strict"){
                $email_pattern = "/^(?!.*\.\.)[a-zA-Z0-9.]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/";
            } else {
                $email_pattern = "/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
            } 
            
            
            //validate email function, check which part of the email contains an issue.
            function email_issue_detector($email){
                $username_pattern = "/^[a-zA-Z0-9._+-]+@/";
                $at_symbol = "/.@./";
                $domain_pattern = "/@.[a-zA-Z0-9.-]+.\./";
                $top_lvl_domain = "/.\.[a-zA-Z]{2,}$/";
                if (!preg_match($username_pattern,$email)) {
                    echo "<li>There is an issue with your username.</li>";
                }
                if (!preg_match($at_symbol,$email) || preg_match_all($at_symbol,$email) > 1) {
                    echo "<li>Addresses can only contain one @ symbol, with text either side.</li>";
                }
                if (!preg_match($domain_pattern,$email)) {
                    echo "<li>Invalid Domain name.</li>";
                }
                if (!preg_match($top_lvl_domain,$email)) {
                    echo "<li>Invalid Top Level Domain.</li>";
                }
                if ($_POST["mode"] == "strict") {
                    echo"<li>Strict Mode Enabled, special characters disabled</li>";
                }

            }

            //return a table of results with valid emails in green and invalid emails in red accompanied with an explanation of the issue.
            if (isset($_POST["emails"]) && trim($_POST["emails"]) != "") {
                //convert special characters to html
                $inserted_text = htmlspecialchars($_POST["emails"]);
                //turn the comma seperated values into an array
                $emails_array = explode(",",$inserted_text);
                echo "<table class = 'results'>";
                echo "<tr><th>Inserted Email</th>";
                echo "<th>Detected Issues</th></tr>";
                //process each email
                foreach ($emails_array as $email) {
                    echo "<tr>";
                    //remove whitespaces
                    $email = trim($email);
                    //if the email is valid display it
                    if (preg_match($email_pattern, $email, $match)) {
                        echo "<td><span class = 'valid'>$email</span></td>";
                        echo "<td><span class = 'no_issue'>None</span>";
                    } else { //if the email is not valid call the issue detector
                        echo "<td><span class='invalid'>$email</span>";
                        echo "<td><ul class='alert'>";
                        email_issue_detector($email);
                        echo "</ul></td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='alert'>Please insert a valid comma seperated list of email addresses</p>";
            }


        
        
        ?>
    </section>
    
</body>
</html>