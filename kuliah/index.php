<html>
    <head>
        <body>

            <?php
                printf ("formulir pendaftaran siswa");
                echo "<br>";

                
                echo "<label>nama calon siswa               :</label>";
                echo '<input type="text" nama="name"';
                echo "<br>";

                echo "<br>";
                echo "<label>tempat/tanggal lahir       :</label>";
                echo '<input type="text" tempat="place"';
                echo '<input type="date" tanggal="date"';

                


            ?>

            <table table style="border-collapse:collapse ;" border="1">
                <tr>
                    <td>no</td>
                    <td>keterangan</td>
                </tr>
                
                <?php for ($i=1;$i<=5;$i++) { ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </table>
        </body>
    </head>
</html>


