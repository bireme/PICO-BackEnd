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
                    <tr><td>Query</td>
                        <td><input type="text" name="Equation" value="dengue" /> </td></tr>
                    <tr><td>Languages </td><td>
                            <ul>
                                <li><input type="checkbox" name="Languages[]" value="en" checked />English </li>
                                <li><input type="checkbox" name="Languages[]" value="es" />Spanish </li>
                                <li><input type="checkbox" name="Languages[]" value="pt" />Portuguese </li>
                            </ul>
                        </td></tr>
                </tbody>
            </table>

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