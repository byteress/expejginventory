<tr x-data="{ edit: false }">
    <td><span x-show="!edit">{{ $this->getExpenseName($expense) }}</span> <select  x-show="edit" wire:model="expense" class="form-control" id="expense_type">
            @foreach($expenses as $expense)
                <option value="{{ $expense->value }}">{{ $expense->displayName() }}</option>
            @endforeach
        </select>
    </td>
    <td><span x-show="!edit">@money($amount, 'PHP', true)</span> <input wire:model="amount" x-show="edit" type="number" min="0.01" step="0.01" required class = "form-control" ></td>
    <td><span x-show="!edit">{{ $description }}</span> <textarea x-show="edit" wire:model="description" class="form-control" id="details" rows="3"></textarea></td>
    @hasrole('admin')
    <td><span>{{ $this->getBranchName($branchId) }}</span></td>
    @endhasrole
    <td>
        <a x-show="!edit" x-on:click="edit = true" href="javascript:void(0)" class="btn btn-success btn-circle btn-sm ml-2">
            <i class="fa fa-pen"></i>
        </a>
        <a x-show="!edit" wire:click="delete" wire:confirm="Are you sure you want to delete this item?" href="javascript:void(0)" class="btn btn-danger btn-circle btn-sm ml-2">
            <i class="fa fa-trash"></i>
        </a>

        <button x-show="edit" x-on:click="edit = false" wire:click="submit" class="btn btn-success btn-circle btn-sm ml-2">
            <i class="fa fa-check"></i>
        </button>
        <a x-show="edit" x-on:click="edit = false" href="javascript:void(0)" class="btn btn-danger btn-circle btn-sm ml-2">
            <i class="fa fa-times"></i>
        </a>
    </td>
</tr>
