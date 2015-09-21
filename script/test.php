<?

include_once( dirname( dirname( __FILE__ ) ).'/includes/application.php' );

Application::run('config');

for ( $i = 0; $i < 10; $i++ )
{
	if ( !isset( $argv[ $i ] ) )
	{
		$argv[ $i ] = null;
	}
}

Console::writeln('Script module loaded.');

switch ( $argv[1] )
{
	case 'adduser':
	
		$Admin = new Admin();
		if ( $argv[2] )
		{
			echo "Adding user ".$argv[2];
			$Admin->Login = $argv[2];
			$Admin->Password = Admin::pwd( $argv[3] );
			$Admin->IsSuper = 1;
			if ( $Admin->save() )
			{
				echo " - OK\n";
			}
			else
			{
				echo " - FAILED\n";
			}
		}
		else
		{
			echo "Login is not set\n";
		}
	
		break;
	
	case 'images':
		
		$Product = new Product();
		foreach ( $Product->findList() as $Product )
		{
			$f1 = File::path( $Product, 1 );
			$f2 = File::path( $Product, 2 );
			File::restore( $f2 );
			copy( $f1, $f2 );
			File::upload( $Product , $f2 );
		}
		
		break;
		
	case 'add':
		// the function adds controller + view + model if it does not exist
		
		if ( !$argv[2] )
		{
			Console::writeln("Define controller");
			break;
		}
		if ( !$argv[3] )
		{
			Console::writeln("Define model");
			break;
		}
		
		$c = explode(':', $argv[2]);
		$m = explode(':', $argv[3]);
		
		$ctrls = array();
		$models = array();
		
		$break = false;
		
		foreach ( $c as $path )
		{
			$name = 'Controller_'.String::toFirstCase( $path, '_', '/' );
			if ( class_exists( $name ) )
			{
				$ctrls[] = array( $path, new $name() );
			}
			else if ( count( $ctrls ) )
			{
				Console::writeln("Controller not found: ".$name);
				$break = true;
				break;
			}
			else
			{
				$ctrls[] = array( $path, $name );
			}
		}
		foreach ( $m as $path )
		{
			$name = String::toFirstCase( $path, '_', '/' );
			if ( class_exists( $name ) )
			{
				$models[] = new $name();
			}
			else if ( count( $models ) == 0 )
			{
				Console::writeln("Model not found: ".$name);
				$break = true;
				break;
			}
			else
			{
				$models[] = $name;
			}
		}
		
		if ( $break )
		{
			break;
		}
		
		
		
		break;
		
	case 'units':
		
		$Unit = new Product_Unit();
		foreach ( $Unit->findList( array( 'Name <> -' ) ) as $Unit )
		{
			$Unit->Name = rtrim( $Unit->Name, 'м' ).'мм';
			$Unit->save();
		}
		
		break;
		
	case 'names':
	
		$Product = new Product();
		$Layout = new Product_Layout_Standard();
		$params = array();
		$params[] = 'CategoryId = '.$Layout->getCategory()->Id;
		foreach ( $Product->findList( $params ) as $Product )
		{
			if ( strpos( $Product->Name, 'поликарбонат' ) > 0 )
			{
				continue;
			}
			$Product->Name .= ' поликарбонат "'.$Product->getBrand()->Name.'"';
			$Product->save();
		}
		
		break;
		
	case 'mail':
	
		Console::writeln( sprintf('%dMb', memory_get_peak_usage(true) / 1024 / 1024) );
	
		if (mail($argv[2], 'Hello from ankor.com.ua', 'Here is the test email'))
		{
			Console::writeln('Email sent');
		}
		else
		{
			Console::writeln('Failed');
		}
		break;
	
}


echo "\n";
