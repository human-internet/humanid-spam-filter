<?php

namespace humanid_spam_filter;

class Migration {
	private string $table_name;
	private array $columns;
	private static array $migrations = [];
	private bool $is_update;
	private static int $revisions = 0;
	private int $revision_id = 0;

	public function __construct( string $table_name, bool $is_update = false ) {
		$this->table_name = HIDSF_TABLE_PREFIX . $table_name;
		$this->is_update  = $is_update;
		$this->columns    = [];
		if ( $is_update ) {
			$this->revision_id = self::$revisions + 1;
			self::$revisions ++;
		}
		array_push( self::$migrations, $this );

		return $this;
	}

	public function string( string $name, int $size = 255 ): Column {
		$column = new Column( $name, [ 'VARCHAR(' . $size . ')' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;

	}

	public function text( string $name ): Column {
		$column = new Column( $name, [ 'TEXT' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function integer( string $name ): Column {
		$column = new Column( $name, [ 'INTEGER', 'SIGNED' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function bigInt( string $name ): Column {
		$column = new Column( $name, [ 'BIGINT', 'SIGNED' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function boolean( string $name ): Column {
		$column = new Column( $name, [ 'BOOL' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function id(): Column {
		$column = new Column( 'id', [
			'BIGINT',
			'UNSIGNED',
			'AUTO_INCREMENT',
			'PRIMARY KEY'
		], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function timestamps() {
		$this->dateTime( 'created_at' )->nullable();
		$this->dateTime( 'updated_at' )->nullable();
	}

	public function softDelete() {
		$this->boolean( 'deleted' )->nullable()->default( 0 );
	}

	/*public function longText( string $name ) {
		$column = new Column( $name, [ 'TEXT' ] );
		array_push( $this->columns, $column );

		return $column;
	}*/

	public function dateTime( string $name ): Column {
		$column = new Column( $name, [ 'DATETIME' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function date( string $name ): Column {
		$column = new Column( $name, [ 'DATE' ], [ 'is_update' => $this->is_update ] );
		array_push( $this->columns, $column );

		return $column;
	}

	public function dropColumn( $name ): void {
		$column = new Column( $name, [], [ 'is_delete' => true ] );
		array_push( $this->columns, $column );
	}

	/*public function change( $name, $new_name ): Column {
		$column = new Column( $name, [], [ 'new_name' => $new_name, 'is_change' => true ] );
		array_push( $this->columns, $column );

		return $column;
	}*/

	public function rename( $name, $new_name ): void {
		$column = new Column( $name, [], [ 'new_name' => $new_name, 'is_rename' => true ] );
		array_push( $this->columns, $column );
	}

	/**
	 * checks if a migration has a column
	 *
	 * @param string $field
	 *
	 * @return boolean
	 * @since v1.0.0
	 */
	public function hasColumn( string $field ): bool {
		foreach ( $this->columns as $column ) {
			if ( $column->getName() == $field ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @since v1.0.0
	 * Creates a table
	 */
	public function up() {
		global $wpdb;
		if ( $this->is_update ) {
			$this->update();
		} else {
			$column_string = '';
			foreach ( $this->columns as $column ) {
				$column_string .= $column->toString() . ',';
			}
			$column_string = rtrim( $column_string, ", " );
			$query         = $wpdb->prepare( "CREATE TABLE IF NOT EXISTS `%s`(%s)", [
				$this->table_name,
				$column_string
			] );

			$wpdb->query( $query );

		}
	}

	/**
	 * @since v1.0.0
	 * Updates a table
	 */
	public function update() {
		global $wpdb;
		$last_revision_run = get_option( HIDSF_TABLE_PREFIX . '_last_revision', 0 );
		if ( $last_revision_run < $this->revision_id ) {
			foreach ( $this->columns as $column ) {
				$query = $wpdb->prepare( "ALTER TABLE `%s` %s", [ $this->table_name, $column->toString() ] );
				$wpdb->query( $query );
			}
			update_option( HIDSF_TABLE_PREFIX . '_last_revision', $this->revision_id );
		}
	}

	public function getTableName(): string {
		return $this->table_name;
	}

	/**
	 * @since v1.0.0
	 * Deletes a table
	 */
	public function down() {
		global $wpdb;
		if ( ! $this->is_update ) {
			$query = $wpdb->prepare( "DROP TABLE IF EXISTS %s", [ $this->table_name ] );
			$wpdb->query( $query );
		}
	}


	/**
	 * @param string $table
	 * @param string $field
	 * @param string $type
	 * @param string $default
	 *
	 * @return void
	 */
	public static function addColumn( string $table, string $field, string $type, string $default = '' ) {
		global $wpdb;
		$query   = $wpdb->prepare( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '%s' AND column_name = '%s'", [
			$table,
			$field
		] );
		$results = $wpdb->get_results( $query );
		if ( empty( $results ) ) {
			$default_string = is_numeric( $default ) ? "DEFAULT $default" : "DEFAULT " . "'$default'";
			$query          = $wpdb->prepare( "ALTER TABLE  %s  ADD  %s  %s  NOT NULL %s", [
				$table,
				$field,
				$type,
				$default_string
			] );
			$wpdb->query( $query );
		}
	}

	/**
	 * @since v1.0.0
	 * Run all migrations
	 */
	public static function runMigrations() {
		foreach ( self::$migrations as $migration ) {
			$migration->up();
		}
	}

	/**
	 * @since v1.0.0
	 * Run update migrations
	 */
	public static function runUpdateMigrations() {
		$migrations = array_filter( self::$migrations, function ( $migration ) {
			return $migration->is_update;
		} );
		foreach ( $migrations as $migration ) {
			$migration->update();
		}
	}

	/**
	 * @param string $table_name Name of the table
	 *
	 * @since v1.0.0
	 * Creates a table
	 */
	public static function runMigration( string $table_name ) {
		$table_name = HIDSF_TABLE_PREFIX . trim( $table_name );
		foreach ( self::$migrations as $migration ) {
			if ( $migration->getTableName() == $table_name ) {
				$migration->up();
			}
		}
	}

	/**
	 * @param string $table_name Name of the table without the prefix
	 *
	 * @since v1.0.0
	 * Delete a particular table
	 */
	public static function drop( string $table_name ) {
		$table_name = HIDSF_TABLE_PREFIX . trim( $table_name );
		foreach ( self::$migrations as $migration ) {
			if ( $migration->getTableName() == $table_name ) {
				$migration->down();
			}
		}
	}

	/**
	 * @since v1.0.0
	 * Deletes all tables
	 */
	public static function dropAll(): void {
		foreach ( self::$migrations as $migration ) {
			$migration->down();
		}
		update_option( HIDSF_TABLE_PREFIX . '_last_revision', 0 );

	}


	/**
	 * @since v1.0.0
	 * Deletes and recreate database tables
	 */
	public static function refresh(): void {
		self::dropAll();
		self::runMigrations();
	}

	/**
	 * Returns a migration instance
	 * @since v1.0.0
	 */
	public static function getMigration( string $table_name, bool $is_full_table_name = false ) {
		$table_name = $is_full_table_name ? $table_name : HIDSF_TABLE_PREFIX . trim( $table_name );
		foreach ( self::$migrations as $migration ) {
			if ( $migration->getTableName() == $table_name ) {
				return $migration;
			}
		}

		return false;
	}
}

class Column {
	private string $name;
	private array $attributes;
	private bool $is_update;
	private bool $is_delete;
	private bool $is_change;
	private bool $is_rename;
	private string $new_name;

	public function __construct( string $name, array $attributes = [], array $extras = [] ) {
		$this->name      = $name;
		$default_extras  = array(
			'is_update' => false,
			'is_delete' => false,
			'is_change' => false,
			'is_rename' => false,
			'new_name'  => ''
		);
		$extras          = array_merge( $default_extras, $extras );
		$this->is_update = $extras['is_update'];
		$this->is_rename = $extras['is_rename'];
		$this->is_delete = $extras['is_delete'];
		$this->is_change = $extras['is_change'];
		$this->new_name  = $extras['new_name'];

		array_push( $attributes, 'NOT NULL' );
		$this->attributes = $attributes;
	}

	public function nullable(): Column {
		if ( ( $key = array_search( 'NOT NULL', $this->attributes ) ) !== false ) {
			unset( $this->attributes[ $key ] );
			$this->attributes = array_values( $this->attributes );
		}
		array_push( $this->attributes, 'NULL' );

		return $this;
	}

	public function unsigned(): Column {
		if ( ( $key = array_search( 'SIGNED', $this->attributes ) ) !== false ) {
			unset( $this->attributes[ $key ] );
			$this->attributes = array_values( $this->attributes );
		}
		array_splice( $this->attributes, 1, 0, 'UNSIGNED' );

		return $this;
	}

	public function primary(): Column {
		array_push( $this->attributes, 'PRIMARY KEY' );

		return $this;
	}

	public function autoIncrement(): Column {
		array_push( $this->attributes, 'AUTO_INCREMENT' );

		return $this;
	}

	public function default( $value ): Column {
		array_push( $this->attributes, 'DEFAULT' );
		array_push( $this->attributes, $value );

		return $this;
	}

	public function toString(): string {
		$attributes = implode( ' ', $this->attributes );

		if ( $this->is_delete ) {
			return ' DROP COLUMN `' . $this->name . '`';
		} else if ( $this->is_update ) {
			return ' ADD `' . $this->name . '` ' . $attributes;
		} else if ( $this->is_change ) {
			return ' CHANGE `' . $this->name . '` `' . $this->new_name . '` ' . $attributes;
		} else if ( $this->is_rename ) {
			return ' RENAME COLUMN `' . $this->name . '` TO `' . $this->new_name . '`';
		} else {
			return '`' . $this->name . '` ' . $attributes;
		}
	}

	public function getName(): string {
		return $this->name;
	}
}
