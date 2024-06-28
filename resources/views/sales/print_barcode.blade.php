@extends('layouts.navigation')
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <h2>PRINT BARCODE STICKER</h2>


                        <div id='DivIdToPrint' style="display: flex !important; justify-content:space-evenly; ">

                            <div style="display: flex !important; justify-content:space-evenly; margin-top:10px">
                                <div style="margin-right: 5px">
                                    <div style="width:2in;height: 1in;padding: 5px;border-radius: 5px;font-weight: 600;">
                                        <div style="text-align: center;font-size: 16px;">
                                            {{ $products->name }}

                                        </div>



                                        <div style="margin-left:15px;">
                                            <img style="width: 90%;  height:30%;"
                                                src="../barcodes/{{ $products->bar_code }}.png" alt="barcode">

                                        </div>

                                        <div style="text-align: center; font-size: 15px;">
                                            {{ $products->bar_code }}<br>
                                            <span
                                                style="line-height: 12px">{{ 'Rs.' . number_format($products->sales_price, 2) }}</span>
                                        </div>

                                    </div>
                                </div>
                                <div style="margin-left: 25px">
                                    <div style="width:2in;height: 1in;padding: 5px;border-radius: 5px;font-weight: 600; margin-left:20px">
                                        <div style="text-align: center;font-size: 16px;">
                                            {{ $products->name }}

                                        </div>



                                        <div style="margin-left:15px;">
                                            <img style="width: 90%;  height:30%;"
                                                src="../barcodes/{{ $products->bar_code }}.png" alt="barcode">

                                        </div>

                                        <div style="text-align: center; font-size: 15px;">
                                            {{ $products->bar_code }}<br>
                                            <span
                                                style="line-height: 12px">{{ 'Rs.' . number_format($products->sales_price, 2) }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type='button' id='btn' value='Print' onclick='printDiv();'>





                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script>
    function printDiv() {

        var divToPrint = document.getElementById('DivIdToPrint');

        var newWin = window.open('', 'Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

        newWin.document.close();

        setTimeout(function() {
            newWin.close();
        }, 10);

    }
</script>
