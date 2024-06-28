<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>print</title>
    <style>
        .account {
            border: 2px solid black;
            width: 100%;
            color: black;
            font-size: 18px;
        }

        .accountTd {
            border: 1px solid rgb(216, 204, 204);
            border-right: 2px solid black;
            padding: 3px;
        }

        .borderTd {
            border-bottom: 1px solid black;
        }

        .borderTop {
            border-top: 1.1px solid black;
        }

        .tdBold {
            border-right: 3px solid black;
        }

        .tdWidth {
            width: 150px;

        }

        .borderBottom {
            border-bottom: 2px solid black;
        }

        .subButton {
            margin-top: 29px;
        }

        .dash {
            border-bottom: 4px solid black
        }

        .tdright {
            text-align: right;
        }

        .borderBottom2 {
            border-bottom: 1.1px solid black;
        }
    </style>
</head>

<body>
    <table class="account" id="printprofitlossTable">
        <thead>
            <tr>
                <td class="borderBottom accountTd">Description</td>
                <td class="borderBottom accountTd">NOTE</td>
                <td class="tdWidth borderBottom accountTd">(Rs.)</td>
                <td class="tdWidth tdBold borderBottom accountTd">(Rs.)</td>
            </tr>

            <tr>
                <td class="accountTd"> SALES </td>
                <td class="accountTd tdright">10</td>
                <td class="tdWidth accountTd"></td>
                <td class="tdWidth tdBold tdright accountTd">
                    {{-- {{ number_format($salesamount, 2) }} --}}
                    1000
                </td>
            </tr>

            <tr style="height:30px">
                <td class="accountTd">OPENING STOCK ON (01.04.2023)</td>
                <td class="accountTd"> </td>
                <td class=" tdright accountTd">
                    {{-- {{ number_format($opening_stockamount, 2) }} --}}
                    200
                </td>
                <td class="tdBold accountTd"></td>
            </tr>

            <tr>
                <td class="accountTd">PURCHASES</td>
                <td class="accountTd tdright">11</td>
                <td class="borderTd tdright accountTd">
                    {{-- {{ number_format($purchaseamount, 2) }} --}}
                    500
                </td>
                <td class="tdBold accountTd"></td>
            </tr>

            <tr>
                <td class="accountTd"> </td>
                <td class="accountTd"> </td>
                {{-- <td class="tdright accountTd">
                    {{ number_format($purchaseamount + $opening_stockamount, 2) }} --}}
                600
                </td>
                <td class="tdBold tdright accountTd"></td>
            </tr>

            <tr>
                <td class="accountTd">CLOSING STOCK ON(31.03.2024)</td>
                <td class="accountTd"> </td>
                <td class="borderTd tdright accountTd">
                    {{-- ({{ number_format($closing_stockamount, 2) }}) --}}
                    1020
                </td>
                <td class="borderTd tdBold tdright accountTd">
                    {{-- ({{ number_format($purchaseamount + $opening_stockamount - $closing_stockamount, 2) }}) --}}
                    1000
                </td>
            </tr>


            <tr>
                <td class="accountTd">GROSS PROFIT</td>
                <td class=" accountTd"></td>
                <td class=" accountTd"></td>
                <td class="tdright tdBold accountTd">
                    {{-- @php
                        $gross_profit = $salesamount - ($purchaseamount + $opening_stockamount - $closing_stockamount);
                    @endphp --}}
                    <b>
                        {{-- {{ number_format($gross_profit, 2) }}  --}}1000
                    </b>
                </td>
            </tr>

            <tr>
                <td class="accountTd">OTHER INCOME</td>
                <td class="accountTd tdright">12</td>
                <td class="tdright accountTd"></td>
                <td class="tdright tdBold borderTd accountTd">
                    {{-- {{ number_format($other_income_amount, 2) }}8000 --}}
                </td>
            </tr>

            <tr>
                <td class="accountTd"></td>
                <td class="accountTd"> </td>
                <td class="tdright accountTd"></td>
                <td class="tdBold tdright accountTd">
                    {{-- {{ number_format($gross_profit + $other_income_amount, 2) }} --}}200
                </td>
            </tr>

            <tr>
                <td class="accountTd">ADMINISTRATIVE EXPENSES</td>
                <td class="accountTd tdright">13</td>
                <td class=" tdright accountTd"></td>
                <td class=" tdBold tdright accountTd">
                    {{-- ({{ number_format($administrative_expenses, 2) }}) --}}1000
                </td>
            </tr>

            <tr>
                <td class="accountTd">SELLING & DISTRIBUTION EXPENSES</td>
                <td class="accountTd tdright">14</td>
                <td class="accountTd"></td>
                <td class=" tdBold tdright accountTd">
                    {{-- ({{ number_format($selling_distribution_expenses, 2) }}) --}}100
                </td>
            </tr>

            <tr>
                <td class="accountTd">FINANCIAL EXPENSES</td>
                <td class="accountTd tdright">15</td>
                <td class="accountTd"></td>
                <td class="tdBold borderTd tdright accountTd">
                    {{-- ({{ number_format($financial_expenses, 2) }}) --}}300
                </td>
            </tr>

            <tr>
                <td class="accountTd">NET PROFIT</td>
                <td class="accountTd"> </td>
                <td class="accountTd"></td>
                <td class="tdBold tdright accountTd">
                    {{-- <b> {{ number_format($gross_profit + $other_income_amount - ($administrative_expenses + $selling_distribution_expenses + $financial_expenses), 2) }} --}}
                    100
                    </b>
                </td>
            </tr>
        </thead>
    </table>
</body>

</html>
