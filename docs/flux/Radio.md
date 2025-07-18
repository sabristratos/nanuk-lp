Select one option from a set of mutually exclusive choices. Perfect for single-choice questions and settings.

```
<flux:radio.group wire:model="payment" label="Select your payment method">    <flux:radio value="cc" label="Credit Card" checked />    <flux:radio value="paypal" label="Paypal" />    <flux:radio value="ach" label="Bank transfer" /></flux:radio.group>
```

## [With descriptions](https://fluxui.dev/components/radio#with-descriptions)

Align descriptions for each radio directly below its label.

<flux:radio.group label="Role"> <flux:radio name="role" value="administrator" label="Administrator" description="Administrator users can perform any action." checked /> <flux:radio name="role" value="editor" label="Editor" description="Editor users have the ability to read, create, and update." /> <flux:radio name="role" value="viewer" label="Viewer" description="Viewer users only have the ability to read. Create, and update are restricted." /></flux:radio.group>

## [Within fieldset](https://fluxui.dev/components/radio#within-fieldset)

Group radio inputs inside a fieldset and provide more context with descriptions for each radio option.

```
<flux:fieldset>    <flux:legend>Role</flux:legend>    <flux:radio.group>        <flux:radio            value="administrator"            label="Administrator"            description="Administrator users can perform any action."            checked        />        <flux:radio            value="editor"            label="Editor"            description="Editor users have the ability to read, create, and update."        />        <flux:radio            value="viewer"            label="Viewer"            description="Viewer users only have the ability to read. Create, and update are restricted."        />    </flux:radio.group></flux:fieldset>
```

## [Segmented](https://fluxui.dev/components/radio#segmented)

A more compact alternative to standard radio buttons.


```
<flux:radio.group wire:model="role" label="Role" variant="segmented">    <flux:radio label="Admin" />    <flux:radio label="Editor" />    <flux:radio label="Viewer" /></flux:radio.group>
```

You can also use the size="sm" prop to make the radios smaller.

```
<flux:radio.group wire:model="role" label="Role" variant="segmented" size="sm">    <flux:radio label="Admin" />    <flux:radio label="Editor" />    <flux:radio label="Viewer" /></flux:radio.group>
```

## [Segmented with icons](https://fluxui.dev/components/radio#segmented-with-icons)

Combine segmented radio buttons with icon prefixes.

```
<flux:radio.group wire:model="role" variant="segmented">    <flux:radio label="Admin" icon="wrench" />    <flux:radio label="Editor" icon="pencil-square" />    <flux:radio label="Viewer" icon="eye" /></flux:radio.group>
```

## [Radio cards](https://fluxui.dev/components/radio#radio-cards)

A bordered alternative to standard radio buttons.

```
<flux:radio.group wire:model="shipping" label="Shipping" variant="cards" class="max-sm:flex-col">    <flux:radio value="standard" label="Standard" description="4-10 business days" checked />    <flux:radio value="fast" label="Fast" description="2-5 business days" />    <flux:radio value="next-day" label="Next day" description="1 business day" /></flux:radio.group>
```

You can swap between vertical and horizontal card layouts by conditionally applying flex-col based on the viewport.

```
<flux:radio.group ... class="max-sm:flex-col">    <!-- ... --></flux:radio.group>
```

## [Vertical cards](https://fluxui.dev/components/radio#vertical-cards)

You can arrange a set of radio cards vertically by simply adding the flex-col class to the group container.

This variant is only available in the Pro version of Flux.

```
<flux:radio.group label="Shipping" variant="cards" class="flex-col">    <flux:radio value="standard" label="Standard" description="4-10 business days" />    <flux:radio value="fast" label="Fast" description="2-5 business days" />    <flux:radio value="next-day" label="Next day" description="1 business day" /></flux:radio.group>
```

## [Cards with icons](https://fluxui.dev/components/radio#cards-with-icons)

You can arrange a set of radio cards vertically by simply adding the flex-col class to the group container.

This variant is only available in the Pro version of Flux.

```
<flux:radio.group label="Shipping" variant="cards" class="max-sm:flex-col">    <flux:radio value="standard" icon="truck" label="Standard" description="4-10 business days" />    <flux:radio value="fast" icon="cube" label="Fast" description="2-5 business days" />    <flux:radio value="next-day" icon="clock" label="Next day" description="1 business day" /></flux:radio.group>
```

## [Cards without indicators](https://fluxui.dev/components/radio#cards-without-indicators)

For a cleaner look, you can remove the radio indicator using :indicator="false".

This variant is only available in the Pro version of Flux.

```
<flux:radio.group label="Shipping" variant="cards" :indicator="false" class="max-sm:flex-col">    <flux:radio value="standard" icon="truck" label="Standard" description="4-10 business days" />    <flux:radio value="fast" icon="cube" label="Fast" description="2-5 business days" />    <flux:radio value="next-day" icon="clock" label="Next day" description="1 business day" /></flux:radio.group>
```

## [Custom card content](https://fluxui.dev/components/radio#custom-card-content)

You can compose your own custom cards through the flux:radio component slot.

This variant is only available in the Pro version of Flux.

<flux:radio.group label="Shipping" variant="cards" class="max-sm:flex-col"> <flux:radio value="standard" checked> <flux:radio.indicator /> <div class="flex-1"> <flux:heading class="leading-4">Standard</flux:heading> <flux:text size="sm" class="mt-2">4-10 business days</flux:text> </div> </flux:radio> <flux:radio value="fast"> <flux:radio.indicator /> <div class="flex-1"> <flux:heading class="leading-4">Fast</flux:heading> <flux:text size="sm" class="mt-2">2-5 business days</flux:text> </div> </flux:radio> <flux:radio value="next-day"> <flux:radio.indicator /> <div class="flex-1"> <flux:heading class="leading-4">Next day</flux:heading> <flux:text size="sm" class="mt-2">1 business day</flux:text> </div> </flux:radio></flux:radio.group>

## Reference

### [flux:radio.group](https://fluxui.dev/components/radio#fluxradiogroup)

|Prop|Description|
|---|---|
|wire:model|Binds the radio group selection to a Livewire property. See the [wire:model documentation](https://livewire.laravel.com/docs/wire-model) for more information.|
|label|Label text displayed above the radio group. When provided, wraps the radio group in a flux:field component with an adjacent flux:label component. See the [field component](https://fluxui.dev/components/field).|
|description|Help text displayed below the radio group. When provided alongside label, appears between the label and radio group within the flux:field wrapper. See the [field component](https://fluxui.dev/components/field).|
|variant|Visual style of the group. Options: default, segmented, cards.|
|invalid|Applies error styling to the radio group.|

|Slot|Description|
|---|---|
|default|The radio buttons to be grouped together.|

|Attribute|Description|
|---|---|
|data-flux-radio-group|Applied to the root element for styling and identification.|

### [flux:radio](https://fluxui.dev/components/radio#fluxradio)

|Prop|Description|
|---|---|
|label|Label text displayed above the radio button. When provided, wraps the radio button in a flux:field component with an adjacent flux:label component. See the [field component](https://fluxui.dev/components/field).|
|description|Help text displayed below the radio button. When provided alongside label, appears between the label and radio button within the flux:field wrapper. See the [field component](https://fluxui.dev/components/field).|
|value|Value associated with the radio button when used in a group.|
|checked|If true, the radio button is selected by default.|
|disabled|Prevents user interaction with the radio button.|
|icon|Name of the icon to display (for segmented variant).|

|Slot|Description|
|---|---|
|default|Custom content for card variant.|

|Attribute|Description|
|---|---|
|data-flux-radio|Applied to the root element for styling and identification.|
|data-checked|Applied when the radio button is selected.|

### [flux:radio.indicator](https://fluxui.dev/components/radio#fluxradioindicator)

Used for custom radio button layouts in card variant.