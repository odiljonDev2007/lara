<table style="background-color: #f1f1f1; width:100%; align="
       center" width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
    <td align="center" style="vertical-align:top;padding:30px 0;text-align:center">
        <table style="text-align:left; width:680px; margin:0 auto; font-size:14px; border-spacing:0px">
            <tbody>
            <tr>
                <td bgcolor="#fff" style="padding: 15px;">
                    <font color="#777" style="font-size:18px">{{ $value_title }}</font>
                </td>
            </tr>
            <tr>
                <td height="25"></td>
            </tr>
            <tr>
                <td bgcolor="#fff" style="padding: 15px;">
                <p><img src="{{ $APP_URL }}/public/assets/img/logo.png"></p>
                <b>id звонка:</b> {{ $id }}<br>
                <b>Имя:</b> {{ $name_contacts }} <br>
                <b>Телефон:</b> <a href="tel:+7{{ $phone_contacts }}">8{{ $phone_contacts }}</a>
	        	<hr>
                <b>Время звонка:</b> {{ $title_time }}
                <hr>
                <b>Дозвон, сек:</b> {{ $time_call }}
                <hr>
                <b>Всего входящих:</b> {{ $inbound }}, из них пропущенных {{ $inbound_not }} (<b>{{ $percent_inbound_not }}%</b>)<br>
                <b>Всего уникальных:</b> {{ $unique }}, из них пропущенных {{ $unique_not }} (<b>{{ $percent_unique_not }}%</b>)
                <hr>
                <b>Дата отправки:</b> {{ $date }}
                    
                </td>
            </tr>
            <tr>
                <td height="25"></td>
            </tr>
            <tr>
                <td bgcolor="#fff" style="padding: 20px 15px; color: #777; font-size: 14px;">
                    <p>&copy; {{ $year }}</p>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
</tbody>
</table>