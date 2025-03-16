{if isset($customData)}
    <div class="card">
        <div class="card-header">
            <h3>Custom Order Data</h3>
        </div>
        <div class="card-body">
            <p>{$customData nofilter}</p>
        </div>
    </div>
{/if}