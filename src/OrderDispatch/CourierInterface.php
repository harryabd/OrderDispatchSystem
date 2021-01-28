<?php

namespace OrderDispatch;

/**
 * CourierInterface
 *
 * All Courier classes should implement this interface
 */
interface CourierInterface
{
    /**
     * getNextConsignmentNumber
     * Method that follows courier-specific algorithm/service lookup
     *
     * @return integer
     */
    public function getNextConsignmentNumber() : int;

    /**
     * processConsignment
     * Method to tell courier to send a consignment
     *
     * @param Consignment $consignment
     * @return boolean success
     * @throws \Exception on failure to send
     */
    public function processConsignment(Consignment $consignment) : bool;
}