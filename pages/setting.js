/**
 *     Jquery news ticker plugin
 *     Copyright (C) 2011 - 2013 www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function _Jntp_submit()
{
	if(document.Jntp_form.Jntp_text.value=="")
	{
		alert("Please enter your ticker news.")
		document.Jntp_form.Jntp_text.focus();
		return false;
	}
	else if(document.Jntp_form.Jntp_link.value=="")
	{
		alert("Please enter your link.")
		document.Jntp_form.Jntp_link.focus();
		return false;
	}
	else if(document.Jntp_form.Jntp_order.value=="")
	{
		alert("Please enter your display order.")
		document.Jntp_form.Jntp_order.focus();
		return false;
	}
	else if(isNaN(document.Jntp_form.Jntp_order.value))
	{
		alert("Please enter the display order, only number.")
		document.Jntp_form.Jntp_order.focus();
		return false;
	}
	else if(document.Jntp_form.Jntp_group.value=="")
	{
		alert("Please select available group for your news.")
		document.Jntp_form.Jntp_group.focus();
		return false;
	}
	else if(document.Jntp_form.Jntp_dateend.value=="")
	{
		alert("Please enter the expiration date in this format YYYY-MM-DD.")
		document.Jntp_form.Jntp_dateend.focus();
		return false;
	}
}

function _Jntp_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_Jntp_display.action="options-general.php?page=jquery-news-ticker&ac=del&did="+id;
		document.frm_Jntp_display.submit();
	}
}	

function _Jntp_redirect()
{
	window.location = "options-general.php?page=jquery-news-ticker";
}

function _Jntp_help()
{
	window.open("http://www.gopiplus.com/work/2013/10/03/jquery-news-ticker-wordpress-plugin/");
}