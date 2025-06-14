Flux Pro component

This component is only available in the Pro version of Flux.

# Table

Display structured data in a condensed, searchable format.

```
<flux:table :paginate="$this->orders">    <flux:table.columns>        <flux:table.column>Customer</flux:table.column>        <flux:table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</flux:table.column>        <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">Status</flux:table.column>        <flux:table.column sortable :sorted="$sortBy === 'amount'" :direction="$sortDirection" wire:click="sort('amount')">Amount</flux:table.column>    </flux:table.columns>    <flux:table.rows>        @foreach ($this->orders as $order)            <flux:table.row :key="$order->id">                <flux:table.cell class="flex items-center gap-3">                    <flux:avatar size="xs" src="{{ $order->customer_avatar }}" />                    {{ $order->customer }}                </flux:table.cell>                <flux:table.cell class="whitespace-nowrap">{{ $order->date }}</flux:table.cell>                <flux:table.cell>                    <flux:badge size="sm" :color="$order->status_color" inset="top bottom">{{ $order->status }}</flux:badge>                </flux:table.cell>                <flux:table.cell variant="strong">{{ $order->amount }}</flux:table.cell>                <flux:table.cell>                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>                </flux:table.cell>            </flux:table.row>        @endforeach    </flux:table.rows></flux:table><!-- Livewire component example code...    use \Livewire\WithPagination;    public $sortBy = 'date';    public $sortDirection = 'desc';    public function sort($column) {        if ($this->sortBy === $column) {            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';        } else {            $this->sortBy = $column;            $this->sortDirection = 'asc';        }    }    #[\Livewire\Attributes\Computed]    public function orders()    {        return \App\Models\Order::query()            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)            ->paginate(5);    }-->
```

## [Simple](https://fluxui.dev/components/table#simple)

The primary table example above is a full-featured table with sorting, pagination, etc. Here's a clean example of a simple data table that you can use as a simpler starting point.

```
<flux:table>    <flux:table.columns>        <flux:table.column>Customer</flux:table.column>        <flux:table.column>Date</flux:table.column>        <flux:table.column>Status</flux:table.column>        <flux:table.column>Amount</flux:table.column>    </flux:table.columns>    <flux:table.rows>        <flux:table.row>            <flux:table.cell>Lindsey Aminoff</flux:table.cell>            <flux:table.cell>Jul 29, 10:45 AM</flux:table.cell>            <flux:table.cell><flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge></flux:table.cell>            <flux:table.cell variant="strong">$49.00</flux:table.cell>        </flux:table.row>        <flux:table.row>            <flux:table.cell>Hanna Lubin</flux:table.cell>            <flux:table.cell>Jul 28, 2:15 PM</flux:table.cell>            <flux:table.cell><flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge></flux:table.cell>            <flux:table.cell variant="strong">$312.00</flux:table.cell>        </flux:table.row>        <flux:table.row>            <flux:table.cell>Kianna Bushevi</flux:table.cell>            <flux:table.cell>Jul 30, 4:05 PM</flux:table.cell>            <flux:table.cell><flux:badge color="zinc" size="sm" inset="top bottom">Refunded</flux:badge></flux:table.cell>            <flux:table.cell variant="strong">$132.00</flux:table.cell>        </flux:table.row>        <flux:table.row>            <flux:table.cell>Gustavo Geidt</flux:table.cell>            <flux:table.cell>Jul 27, 9:30 AM</flux:table.cell>            <flux:table.cell><flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge></flux:table.cell>            <flux:table.cell variant="strong">$31.00</flux:table.cell>        </flux:table.row>    </flux:table.rows></flux:table>
```

## [Pagination](https://fluxui.dev/components/table#pagination)

Allow users to navigate through different pages of data by passing in any model paginator to the paginate prop.

```
<!-- $orders = \App\Models\Order::paginate(5) --><flux:table :paginate="$orders">    <!-- ... --></flux:table>
```

## [Sortable](https://fluxui.dev/components/table#sortable)

Allow users to sort rows by specific columns using a combination of the sortable, sorted, and direction props.

<flux:table> <flux:table.columns> <flux:table.column>Customer</flux:table.column> <flux:table.column sortable sorted direction="desc">Date</flux:table.column> <flux:table.column sortable>Amount</flux:table.column> </flux:table.columns> <!-- ... --></flux:table>

## Reference

### [flux:table](https://fluxui.dev/components/table#fluxtable)

|Prop|Description|
|---|---|
|paginate|A Laravel paginator instance to enable pagination.|

|Attribute|Description|
|---|---|
|data-flux-table|Applied to the root element for styling and identification.|

### [flux:table.columns](https://fluxui.dev/components/table#fluxtablecolumns)

|Slot|Description|
|---|---|
|default|The table columns.|

### [flux:table.column](https://fluxui.dev/components/table#fluxtablecolumn)

|Prop|Description|
|---|---|
|align|Alignment of the column content. Options: start, center, end.|
|sortable|Enables sorting functionality for the column.|
|sorted|Indicates this column is currently being sorted.|
|direction|Sort direction when column is sorted. Options: asc, desc.|

### [flux:table.rows](https://fluxui.dev/components/table#fluxtablerows)

|Slot|Description|
|---|---|
|default|The table rows.|

### [flux:table.row](https://fluxui.dev/components/table#fluxtablerow)

|Prop|Description|
|---|---|
|key|An alias for wire:key: the unique identifier for the row.|

|Slot|Description|
|---|---|
|default|The table cells for this row.|

### [flux:table.cell](https://fluxui.dev/components/table#fluxtablecell)

|Prop|Description|
|---|---|
|align|Alignment of the cell content. Options: start, center, end.|
|variant|Visual style of the cell. Options: default, strong.|

