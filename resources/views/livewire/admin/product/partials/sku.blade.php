<span>
    @if (!$sku)
        <p wire:poll="checkSku">Generating...</p>
    @else
        <a data-toggle="modal" data-target="#barcode-modal-{{ $productId }}" class="btn btn-secondary" href="#">{{ $sku }}</a>

        <div     wire:ignore.self class="modal fade" id="barcode-modal-{{ $productId }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteModal1Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal1Label">{{ $sku }} Barcode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! DNS1D::getBarcodeHTML($sku, 'EAN13', 2, 100, 'black', 15) !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</span>