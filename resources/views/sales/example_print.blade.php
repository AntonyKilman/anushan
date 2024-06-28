<!DOCTYPE html>
<html lang="en">

<head></head>

<body>
    <div style="display:flex;flex-direction:column;align-items: center;">
        ${$('#image').html()}
        <div style="font-size:15px; font-weight:600;">JAFFNA ELECTRICALS</div>
        <div style="font-size:7px; font-weight:600;">DEALERS IN QUALITY ELECTRICAL & ELECTRONIC ITEMS</div>
    </div>
    <div style="display:flex">
        <span style="flex: 50%; font-size:12px; text-align: left;font-weight:600;">
            <div>Tel : 021-2222353 </div>
        </span>
        <span style="flex: 50%; font-size:12px; text-align: right;font-weight:600;">
            <div>Fax : 021-2224302 </div>
        </span>
    </div>

    <div style="display: flex;border-bottom:0.2px solid black;">
        <span style="flex: 10%; font-size:12px; text-align: left;font-weight:600;">
            <div>No.94(6), Stanley Road, Jaffna. </div>
        </span>
    </div>

    

    <div style="display: flex;border-bottom:0px solid black;"> 
        <span style="flex: 40%; font-size:12px; text-align: left;font-weight:600;">
        <div>Date: ${date}</div><div>Time: ${time}</div></span>
        <span style="flex: 60%; font-size:12px; text-align: right;font-weight:600;"> 
        <div>Cus-Name: ${customerName}</div><div>User: ${billUser}</div></span>
        </div>

        <div style="display: flex;border-bottom:0.2px solid black;">
        <span style="flex: 100%; font-size:12px; text-align: left;font-weight:600;"> 
        <div>Invoice No: ${invoiceNo}</div></span></div>

    <div style="display:flex;flex-direction:column;align-items: center;">
        <div style="font-size:15px; font-weight:600;">Credit Memo</div>
    </div>
    <br>
    ${printArea}

<br> <br>
    <div style="display:flex">
        <span style="flex: 30%; font-size:12px; text-align: left;font-weight:600;">
            <div>...................... </div>
            <div>Prepared By </div>

        </span>
        <span style="flex: 30%; font-size:12px; text-align: center;font-weight:600;">
            <div>.................... </div>
            <div>Checked By </div>
        </span>
        <span style="flex: 30%; font-size:12px; text-align: right;font-weight:600;">
            <div>..................... </div>
            <div>Received By </div>
        </span>
    </div>

    <br>
    <div style="padding-top:10px; font-size:14px;display:flex;flex-direction:column;align-items: center;">
        <div>Thank You ! Come Again</div>
    </div>
    <div style="padding-top:4px; font-size:10px ; display:flex;flex-direction:column;align-items: center;">
        <div>System Developed BY Codevita (Pvt) Ltd</div>
    </div>
</body>

</html>
