<?php

namespace TransferContracts\Exceptions;

enum ErrorCode: int
{
    case CANNOT_TRANSFER_TO_SELF = 4701;
    case EXCEEDED_REQUESTED_QUANTITY = 4702;
    case EXCEEDED_TRANSFERRED_QUANTITY = 4703;
}
