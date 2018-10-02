{* HEADER *}
<div class="crm-section help">If an event has been set to ‘Is this a member only event?’ then only users with one of the set membership statuses below and those that have a website account will be able to register for the event.</div>
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="top"}
</div>

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}
<div class="crm-section">
{foreach from=$elementNames item=elementName}
    {if $elementName == 'memberonly_message'}
        <div id="mem-only-message">
    {/if}
      <div class="crm-field">
        <div class="label">{$form.$elementName.label}</div>
        <div class="content">{$form.$elementName.html}</div>
        <div class="clear"></div>
      </div>
    {if $elementName == 'memberonly_message'}
        </div>
    {/if}
  
{/foreach}
</div>

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

{literal}
<script type="text/javascript">
CRM.$(function($) {
    if ( CRM.$('#display_memberonly_message').prop('checked') == false ) {
        CRM.$('#mem-only-message').hide();
    }
});
</script>
{/literal}