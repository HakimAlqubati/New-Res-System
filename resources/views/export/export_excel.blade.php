<table>
    <thead>
        <tr>
            <th>Product code </th>
            <th>Product name </th>
            <th>Unit </th>
            <th>Quantity</th>
            <th>Branch</th>
        </tr>
    </thead>
    <tbody>
        <?php  
       
        foreach($finalResult as $key => $value) { 
             
          
            if( is_numeric ($key) && ((int)$key) > 0) {
            ?>
        <tr>
            <td>{{ $value?->product_code }}</td>
            <td>{{ $value?->product_name }}</td>
            <td>{{ $value?->unit_name }}</td>
            <td>{{ $value?->available_qty }}</td>
            <td>
                @php
                    if ($key == 1) {
                        echo $finalResult[0]->branch_name;
                    }
                @endphp
            </td>
        </tr>
        <?php
       
            }
     } ?>



    </tbody>
</table>
