{* MTL Start remove register block *}
{if $mark_inactive == 1 }
	{literal}
		<script type="text/javascript">
		CRM.$(function($) {
			CRM.$('a.crm-register-button').parent().parent().remove();
		});
		</script>
	{/literal}
{/if}
{* MTL End remove register block *}

{if $mark_inactive == 1 }
{if $memberonly_message != '' }
<div id="mem_only_message" style="display:none"><div class="help message">{$memberonly_message}</div></div>
    {literal}
        <script type="text/javascript">
        CRM.$(function($) {
            CRM.$('div.crm-event-info-form-block').prepend(CRM.$('#mem_only_message').html());
        });
        </script>
    {/literal}
{/if}
{/if}

{* MTL Start remove custom member_only data block *}
{literal}
	<script type="text/javascript">
		CRM.$(function($) {
			CRM.$("td[id^='member_only_event']").parent().parent().parent().prev('div.messages.help').remove();
			CRM.$("td[id^='member_only_event']").parent().remove();
		});
	</script>
{/literal}
{* MTL End remove custom member_only data block *}