[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]


[{ if $error}]<div class="errorbox">[{ $error }]</div>[{/if}]
[{ if $message}]<div class="messagebox">[{ $message }]</div>[{/if}]

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="shop_license">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>





        <table id="tShopLicense" border="0" width="45%">
        <tr>
            <td class="edittext" width="230" valign="top">
            <form id="myedit1" action="[{ $oViewConf->getSelfLink() }]" method="post">
            [{ $oViewConf->getHiddenSid() }]
            <input type="hidden" name="cl" value="shop_license">
            <input type="hidden" name="fnc" value="deleteSerial">
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="editval[oxuser__oxid]" value="[{ $oxid }]">
            [{ oxmultilang ident="SHOP_LICENSE_SERIAL" }]
            <!-- overall shop serial number: [{$edit->oxshops__oxserial->value}] -->
            </td>
            <td class="edittext" colspan=2>

            [{if $oxserials}]
                <table>
                [{assign var=oddclass value="2"}]
                [{foreach from=$oxserials item=serial}]
                 <tr>
                    [{if $oddclass == 2}]
                        [{assign var=oddclass value=""}]
                    [{else}]
                        [{assign var=oddclass value="2"}]
                    [{/if}]

                    <td class="listitem[{$oddclass}]" nowrap>
                        [{$serial}]
                    </td>
                    <td class=listitem[{$oddclass}]>
                      [{if !$readonly }]
                      <a href="[{$oViewConf->getSelfLink()}]cl=shop_license&amp;serial=[{$serial}]&amp;fnc=deleteserial&amp;oxid=[{$oxid}]" onClick='return confirm("[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]")' class="delete"></a>
                      [{/if}]
                    </td>

                </tr>
                [{/foreach}]
                </table>
            [{/if}]

            </td>
        </tr>
        <tr>
            <td class="edittext" colspan=3>&nbsp;</td>
        </tr>
        </form>

        <form name="myedit2" id="myedit2" action="[{ $oViewConf->getSelfLink() }]" method="post">
            [{ $oViewConf->getHiddenSid() }]
            <input type="hidden" name="cl" value="shop_license">
            <input type="hidden" name="fnc" value="">
            <input type="hidden" name="oxid" value="[{ $oxid }]">
            <input type="hidden" name="editval[oxuser__oxid]" value="[{ $oxid }]">
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="SHOP_LICENSE_NEWSERIAL" }]
            </td>
            <td class="edittext">
              <input type="text" class="editinput" size="36" name="editval[oxnewserial]" style="width: 250px;" [{ $readonly }]>
              [{ oxinputhelp ident="HELP_SHOP_LICENSE_NEWSERIAL" }]
            </td>
            <td class="edittext">
              <input type="submit" class="edittext" name="save" value="&nbsp;&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_SAVE" }]&nbsp;&nbsp;&nbsp;&nbsp;" onClick="Javascript:document.myedit2.fnc.value='save'" [{ $readonly }]>
            </td>
        </tr>
        </form>
          <tr>
            <td class="edittext">
            <br><strong>[{ oxmultilang ident="SHOP_LICENSE_VERSION" }]</strong>
            </td>
            <td class="edittext">
            <b>[{ oxmultilang ident="GENERAL_OXIDESHOP" }]
                [{$oView->getShopEdition()}] [{$oView->getShopVersion()}]_[{$oView->getRevision()}]
                [{if $oView->isDemoVersion()}]
                    [{ oxmultilang ident="SHOP_LICENSE_DEMO" }]
                [{/if}]
            </b>
            </td>
          </tr>

        </table>
        <table id="tVersionInfo" border="0">
        <tr>
            <td>
                <span>[{$aCurVersionInfo}]</span>
            </td>
        </tr>
        </table>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
