	/**
	 * Execute the layer.
	 */
	function execute()
	{
		try
		{
			parent::execute();
			// got sublayer?
			if ($this->layer)
			{
				// it is possible this is used higher up
				$this->contents($this->layer->get_contents());
			}
		}
		catch (\Exception $exception)
		{
			$this->exception($exception, true);
		}
	}
