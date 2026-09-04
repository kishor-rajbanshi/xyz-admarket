if(window.invoke)
var xyz_cta=new invoke();
else
{
   function invoke()
   {
		this.trigger=trigger;
		
		function trigger()
		{   
			ctaparent=window.location.href;
			ctaparentarray=ctaparent.split('#');
			
			ctapath="";
			if(ctaparentarray[1] !="")
			{
				ctapath=ctaparentarray[1];
				
				window.open(ctaparentarray[1],'_blank');
			}
		}
		
		
		
		
   }
   var xyz_cta=new invoke();
}