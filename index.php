<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <form action="processDeCSSearch.php" method="get"> 
            DeCS Extractor for keyword
            <br>
            <table>
                <tbody>
                    <tr><td>KeyWord</td>
                        <td><input type="text" name="keyword" value="dengue" /> </td></tr>
                    <tr><td>Languages (es,en,pt)</td>
                        <td><input type="text" name="languages" value="en,es,pt" /> </td></tr>
                </tbody></table>

            <input type="submit" name="submit" />
        </form>
        <br><br><br><br>
        <form action="processResultsNumber.php" method="get"> 
            Number of results of query<br>
            <table><tbody>
                    <tr><td>Query</td>
                        <td><input type="text" name="query" value="dengue and zika" /> </td></tr>
                    <tr><td><input type="submit" name="submit" /></td></tr>
                </tbody></table>

        </form>


        <a href="index.php"></a>
    </body>
</html>